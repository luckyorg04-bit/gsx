# GSX pre-deployment package

This repository is prepared for a domain/VPS hand-off, but it is **not deployed**. Do not put credentials, private keys, Firebase service accounts, FCM keys, SMTP passwords, or production database dumps in Git.

## Included checks

Run the preflight after the final domain and VPS are supplied:

```bash
chmod +x scripts/gsx-preflight.sh
./scripts/gsx-preflight.sh
```

The script checks required production environment values, HTTPS URL, secure session cookie, lockfiles, installed Composer dependencies, frontend manifest, Composer platform requirements, and Laravel bootstrap. It does not contact external services or verify DNS/TLS from the public internet.

## Required production environment

Set at minimum:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://<final-domain>`
- A unique generated `APP_KEY`
- Production database connection and credentials
- `SESSION_SECURE_COOKIE=true`
- Real mail transport and sender identity
- FCM credentials only if push delivery is enabled
- Correct queue/cache/session drivers for the selected VPS topology

Keep `.env` outside version control and restrict file permissions.

## Release sequence

1. Provision supported PHP version/extensions, database, Node build runtime, web server, and process supervisor.
2. Upload the reviewed source revision and install production dependencies with `composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader`.
3. Build frontend assets with the lockfile: `npm ci` then `npm run production`; do not deploy development dependencies.
4. Configure the production `.env`, generate the app key once, and run this preflight.
5. Run `php artisan migrate --force` after taking a database backup; seed only the explicitly approved production seeders.
6. Run `php artisan storage:link`, `php artisan config:cache`, `php artisan route:cache`, and `php artisan view:cache` where compatible with the installed packages.
7. Configure HTTPS, HTTP-to-HTTPS redirect, HSTS after TLS validation, least-privilege file permissions, queue workers, scheduler, backups, log rotation, and monitoring.
8. Verify login and each role using real test accounts, email delivery, push/web notifications, uploads, reports, and mobile app API connectivity.
9. Capture smoke-test evidence and rollback revision/database backup before opening production traffic.

## Not yet verified / external dependencies

- Public DNS, TLS certificate, server firewall, web server, queue worker, scheduler, backup restore, and production mail/FCM delivery require the final infrastructure and credentials.
- Teacher and Parent Android source projects are not present in this repository. Obtain the official source and written reuse rights before building or redistributing either app.
- Browser end-to-end tests against the deployed site require the final domain, test accounts, and browser-capable runtime.
- A successful CI run is not a substitute for a full penetration test or an independent security assessment.
