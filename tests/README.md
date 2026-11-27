# GSX automated tests

The repository currently contains these feature suites:

- `AuthenticationTest.php` — role login and invalid-credential scenarios.
- `AuthenticationAdvancedTest.php` — rate limiting, logout, remember-me, and role access checks.
- `SchoolStatusLifecycleTest.php` — login behavior when a school is active or inactive.
- `SchoolInitializationTest.php` — school details and academic-year initialization across the June rollover.
- `TeacherStatusLifecycleTest.php` — teacher status lifecycle scenarios.

## Run the suite

Run tests only against the isolated testing database configured in `phpunit.xml`:

```bash
composer install
php artisan test
```

The test configuration targets SQLite in-memory storage. The PHP runtime must have PDO SQLite enabled.

## Important

- Do not point PHPUnit at production or any database containing real school data.
- Some test files are inherited project tests and still require assertion and fixture review.
- A test suite passing does not replace browser-based role workflows, API integration tests, mail delivery checks, or security testing.
- No passing test result is claimed until the suite has been executed in CI or an isolated local environment.
