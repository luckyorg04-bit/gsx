#!/usr/bin/env bash
set -Eeuo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

failures=0
check() {
  local label="$1"
  shift
  if "$@"; then
    printf 'PASS  %s\n' "$label"
  else
    printf 'FAIL  %s\n' "$label"
    failures=$((failures + 1))
  fi
}

env_value() {
  local key="$1"
  sed -n "s/^\${key}=//p" .env | tail -n 1 | tr -d '\r' | sed -E 's/^["'\'']//; s/["'\'']$//'
}

has_env() {
  local key="$1"
  local value
  value="$(env_value "$key")"
  [[ -n "$value" && "$value" != "null" && "$value" != "changeme" ]]
}

check ".env exists" test -f .env
check "Composer lock exists" test -f composer.lock
check "NPM lock exists" test -f package-lock.json
check "Production vendor dependencies installed" test -f vendor/autoload.php
check "Production frontend manifest exists" test -f public/mix-manifest.json

if [[ -f .env ]]; then
  [[ "$(env_value APP_ENV)" == "production" ]] && printf 'PASS  APP_ENV=production\n' || { printf 'FAIL  APP_ENV must be production\n'; failures=$((failures + 1)); }
  [[ "$(env_value APP_DEBUG)" == "false" ]] && printf 'PASS  APP_DEBUG=false\n' || { printf 'FAIL  APP_DEBUG must be false\n'; failures=$((failures + 1)); }
  [[ "$(env_value APP_URL)" == https://* ]] && printf 'PASS  APP_URL uses HTTPS\n' || { printf 'FAIL  APP_URL must be the final HTTPS domain\n'; failures=$((failures + 1)); }
  [[ "$(env_value APP_KEY)" == base64:* ]] && printf 'PASS  APP_KEY is configured\n' || { printf 'FAIL  APP_KEY is missing or invalid\n'; failures=$((failures + 1)); }
  for key in DB_CONNECTION DB_HOST DB_DATABASE DB_USERNAME DB_PASSWORD; do
    has_env "$key" && printf 'PASS  %s configured\n' "$key" || { printf 'FAIL  %s is missing\n' "$key"; failures=$((failures + 1)); }
  done
  [[ "$(env_value SESSION_SECURE_COOKIE)" == "true" ]] && printf 'PASS  SESSION_SECURE_COOKIE=true\n' || { printf 'FAIL  SESSION_SECURE_COOKIE must be true\n'; failures=$((failures + 1)); }
fi

if [[ -x vendor/bin/composer ]]; then
  check "Composer platform requirements" vendor/bin/composer check-platform-reqs --no-dev
elif command -v composer >/dev/null 2>&1 && [[ -f composer.lock ]]; then
  check "Composer platform requirements" composer check-platform-reqs --no-dev
fi

if [[ -f artisan && -f vendor/autoload.php ]]; then
  check "Laravel bootstrap" php artisan about --only=environment
fi

if (( failures > 0 )); then
  printf '\nPre-deployment checks failed: %s\n' "$failures"
  exit 1
fi

printf '\nGSX pre-deployment checks passed. Configure DNS/TLS and review the deployment guide before release.\n'
