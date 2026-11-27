#!/usr/bin/env bash
set -Eeuo pipefail

echo "GSX test environment verification"

required_files=(
  "phpunit.xml"
  "tests/TestCase.php"
  "tests/Feature/AuthenticationTest.php"
  "tests/Feature/AuthenticationAdvancedTest.php"
  "tests/Feature/SchoolStatusLifecycleTest.php"\n  "tests/Feature/SchoolInitializationTest.php"
  "tests/Feature/TeacherStatusLifecycleTest.php"
)

missing=0
for file in "${required_files[@]}"; do
  if [[ -f "$file" ]]; then
    printf 'PASS  %s\n' "$file"
  else
    printf 'FAIL  %s (missing)\n' "$file"
    missing=1
  fi
done

if ! command -v php >/dev/null 2>&1; then
  echo "FAIL  PHP CLI is not installed"
  exit 1
fi

if ! php -m | grep -qi '^pdo_sqlite$'; then
  echo "FAIL  PDO SQLite extension is required for the in-memory test database"
  exit 1
fi

if [[ ! -f vendor/autoload.php ]]; then
  echo "FAIL  Composer dependencies are missing; run composer install"
  exit 1
fi

if [[ "$missing" -ne 0 ]]; then
  echo "FAIL  One or more expected test files are missing"
  exit 1
fi

echo "Running isolated feature/unit tests..."
php artisan test
