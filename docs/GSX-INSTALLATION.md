# GSX installation and pre-deployment checklist

This guide is intentionally CLI-first. The legacy browser installer under `public/installer` is disabled.

## Why the browser installer is disabled

The previous installer exposed server-side Composer/Artisan execution through HTTP and ran `php artisan migrate:fresh --force`. That command drops existing tables before recreating them and is unsafe as a generic production installation step. The database test endpoint also attempted to create databases using values supplied by an HTTP request.

Do not restore the old installer or expose server command execution over HTTP.

## Prerequisites

- PHP 8.4+ with PDO MySQL, mbstring, OpenSSL, tokenizer, JSON, cURL, fileinfo, GD, BCMath, XML and ZIP.
- MySQL version compatible with Laravel 12 and the application's migrations.
- Composer 2.
- Node.js/npm only when rebuilding frontend assets.
- A dedicated database and least-privilege database user.
- Web server document root set to this repository's `public/` directory.

## Safe first-time setup (operator-run CLI)

1. Provision a new, empty database and a dedicated database user outside the application.
2. Clone the repository on the server and enter the project directory.
3. Install production PHP dependencies:

   ```bash
   composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
   ```

4. Create the environment file and edit it locally on the server:

   ```bash
   cp .env.example .env
   ```

   Set at least `APP_NAME=GSX`, the real `APP_URL`, `APP_ENV=production`, `APP_DEBUG=false`, a unique database name/user/password, a valid mail transport, and the correct timezone. Never paste secrets into GitHub.

5. Configure the integrations required by school registration before opening `/register`:

   - `GOOGLE_RECAPTCHA_KEY` and `GOOGLE_RECAPTCHA_SECRET` for the required reCAPTCHA validation.
   - `SMS_GATEWAY_API_KEY`, `SMS_GATEWAY_SENDER_ID` and `SMS_GATEWAY_ROUTE_NO` for the registration OTP sent through MSG91. SMS delivery may incur provider charges.
   - Working SMTP settings and `MAIL_STATUS=on` if verification and administrator notification emails are required.

   The registration flow requires a valid reCAPTCHA and mobile OTP. The OTP screen now supports a rate-limited resend action and validates expiry. Test delivery with a real test number and provider account in staging.

6. Generate the Laravel application key:

   ```bash
   php artisan key:generate
   ```

7. Review pending migrations before applying them. On a genuinely new, empty database only:

   ```bash
   php artisan migrate --force
   ```

   Never use `migrate:fresh`, `migrate:refresh`, or `db:wipe` on a database containing data.

8. Review the seeders, then run them once on the new, empty database to load required reference data and the default subscription plan:

   ```bash
   php artisan db:seed --force
   ```

   In production, `DatabaseSeeder` loads shared reference data only. Demo schools, sample accounts, sample classes, books, notices and payroll records are restricted to `local` and `development`. The seeders are not designed to be rerun against an existing database because several insert fixed records without upsert protection.
9. Create the public storage link:

   ```bash
   php artisan storage:link
   ```

10. If frontend assets must be rebuilt and a lockfile is present:

   ```bash
   npm ci
   npm run production
   ```

11. Set writable permissions narrowly for `storage/` and `bootstrap/cache/`. Do not make the entire project writable by the web-server user.
12. Configure HTTPS, Laravel scheduler, queue workers, mail delivery, backups, monitoring and log rotation.
13. Verify login, role permissions, file uploads, mail, notifications, API routes and critical workflows in staging before production traffic.

## Rollback and data safety

- Take a database backup before every migration on an existing environment.
- Test restore procedures; an untested backup is not a verified backup.
- Apply schema changes through reviewed forward migrations.
- Never expose `.env`, source files, logs, backups, SQL dumps or Composer files through the web root.
- Keep the browser installer disabled. Its endpoint intentionally returns HTTP 410.

## Verification status

The repository now includes a GitHub Actions workflow for Composer validation, PHP tests and the frontend build. The first complete PHP test run passed; frontend build verification and dependency security audits remain pending. A workflow file being present is not a passing result; the complete test-and-frontend-build workflow has not yet passed.

This document is an operator procedure, not evidence that deployment has succeeded. A live database migration, mail test, API integration test, browser walkthrough and production security verification still require an isolated staging environment and credentials. Do not mark GSX production-ready until those checks pass.
