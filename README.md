# GSX — School Management & ERP

GSX is a self-hosted school management platform for school administrators, teachers, parents and operational staff.

This repository is a fork of the GegoK12 project. The upstream MIT license and copyright notice are retained in `LICENSE`. Review third-party dependency and asset licenses before redistribution.

## Technology

- Laravel 12
- PHP 8.4+
- MySQL
- Composer
- Node.js 24 + npm for frontend asset compilation
- Optional Redis

## Server requirements

Required PHP extensions include PDO/MySQL, mbstring, OpenSSL, tokenizer, JSON, cURL, fileinfo, GD, BCMath, XML and ZIP. Configure the web server document root to the `public/` directory. Ensure `storage/` and `bootstrap/cache/` are writable by the web-server user.

## Local / server setup

1. Clone this repository and enter the project directory.
2. Install PHP dependencies: `composer install --no-dev --optimize-autoloader`.
3. Create environment file: `cp .env.example .env`.
4. Set `APP_NAME=GSX`, the production `APP_URL`, database credentials, mail settings and required integrations in `.env`.
5. Generate the application key: `php artisan key:generate`.
6. Run database migrations and seeders only after reviewing the project's installation flow and confirming the target database is empty/backed up.
7. Create the public storage link: `php artisan storage:link`.
8. Build frontend assets: `npm ci && npm run production`.
9. Set `APP_ENV=production` and `APP_DEBUG=false`, then clear/rebuild Laravel caches.
10. Configure HTTPS, scheduled tasks, queue workers, backups and monitoring before production use.

> Important: Never commit a real `.env`, application key, database password, Firebase credential, cloud key or other secret. The values in `.env.example` are placeholders only.

## Mobile apps

The current `GSX` repository contains the Laravel web application and API endpoints. Separate Android/iOS Teacher and Parent app project source code was not found in this repository tree. The mobile API integration and official app distribution/source availability must be verified separately before promising branded GSX mobile builds.

## Branding status

- Laravel application name defaults to GSX.
- Environment template defaults to GSX and Asia/Kolkata.
- Shared login/public logo and public navigation use a GSX text wordmark.
- Browser title, favicon and installable web-app manifest use GSX branding.
- Dashboard, email, notification, uploaded demo logo and mobile-app branding still require a full UI/asset audit.

## License

See [LICENSE](LICENSE) for the upstream license and required notices.
