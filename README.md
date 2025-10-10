# LMS Test Project

This repository contains a Laravel-based learning management system.

## Local setup

1. Install PHP 8.1+, Composer, Node.js, and npm.
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install JavaScript dependencies (optional for front-end assets):
   ```bash
   npm install
   ```
4. Copy the example environment file and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Configure the database connection in `.env`. For a quick SQLite setup you can:
   ```bash
   touch database/database.sqlite
   ```
   Then set `DB_CONNECTION=sqlite` and `DB_DATABASE=` to the absolute path of the file. Alternatively, update `phpunit.xml` to use the in-memory SQLite configuration.

## Running tests

After completing the setup steps above, run the test suite with:

```bash
php artisan test
```

If you only need to run PHPUnit directly, you can also use:

```bash
./vendor/bin/phpunit
```

Both commands require the Composer dependencies to be installed so that `vendor/autoload.php` exists.
