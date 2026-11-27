# GSX — School Management & ERP

GSX is a self-hosted school management platform for school administrators, teachers, parents and operational staff.

This repository is a fork of the GegoK12 project. The upstream MIT license and copyright notice are retained in `LICENSE`. Review third-party dependency and asset licenses before redistribution. GSX is an independent project name; do not imply upstream endorsement.

## Technology

- Laravel 12
- PHP 8.4+
- MySQL
- Composer
- Node.js/npm for frontend asset compilation
- Optional Redis

## Server requirements

Required PHP extensions include PDO/MySQL, mbstring, OpenSSL, tokenizer, JSON, cURL, fileinfo, GD, BCMath, XML and ZIP. Configure the web server document root to the `public/` directory. Ensure `storage/` and `bootstrap/cache/` are writable by the web-server user, using least-privilege permissions.

## Installation

The legacy browser installer is intentionally disabled. It previously exposed Composer/Artisan execution over HTTP and ran `migrate:fresh`, which can drop existing database tables and data. Use the reviewed CLI-first procedure in [docs/GSX-INSTALLATION.md](docs/GSX-INSTALLATION.md). Do not enable the old web installer or run destructive migrations on a database containing data.

## Production safety

- Never commit a real `.env`, application key, database password, Firebase credential, cloud key or other secret.
- Use `APP_ENV=production` and `APP_DEBUG=false`.
- Use a dedicated database and least-privilege database user.
- Review migrations and seeders before running them; default demo credentials must be changed.
- Configure HTTPS, backups, restore testing, scheduler, queue workers, mail and monitoring before production use.

## Mobile apps

The current GSX repository contains the Laravel web application and API endpoints. Separate Android/iOS Teacher and Parent app project source code was not found in this repository tree. Mobile API integration and official app source/distribution availability must be verified separately before promising branded GSX mobile builds.

## Branding and audit status

- Laravel application name, environment template, browser title, favicon and web-app manifest use GSX branding.
- Shared login/public wordmark and public navigation use the GSX brand partial.
- Tenant school names and uploaded school logos remain data-driven so each school can retain its own identity.
- The legacy browser installer has been disabled; a CLI-only installation guide replaces it.
- Demo-account seeders are restricted to local/development/testing so factory users with the shared demo password are not created in production.
- Docker development defaults and Nginx protections have been hardened; the compose stack binds published ports to localhost.
- A GitHub Actions workflow has been added for Composer validation, PHP tests and frontend asset builds; its first successful run is still pending.
- Full role-screen, email/notification, image-asset and runtime QA audits remain in progress; this repository is not yet declared production-ready.

## License

See [LICENSE](LICENSE) for the upstream license and required notices.
