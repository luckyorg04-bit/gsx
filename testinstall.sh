#!/bin/sh
set -eu

cat <<'NOTICE'
The legacy testinstall.sh script has been retired.
It previously ran migrate:fresh and db:seed without safeguards.
Use the isolated test database and the reviewed CLI instructions instead:
  php artisan test
NOTICE

exit 1
