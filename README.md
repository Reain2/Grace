# Grace

Grace is a multi-tradition spiritual-life platform for demo and local testing. It supports six traditions, role-based access, manual verification, content isolation, reading plans, reminders, events, journaling, calendars, notifications, and admin auditing.

## Repository

```text
https://github.com/Reain2/Grace
```

## Prerequisites

Choose one local setup:

### Docker setup

- Git
- Docker Desktop
- Composer
- Node.js and npm

### SQLite fallback

- Git
- PHP 8.4+
- Composer
- Node.js and npm
- SQLite PHP extension

## Option A: Docker + MySQL

```bash
git clone https://github.com/Reain2/Grace.git
cd Grace
cp .env.example .env
composer install
npm install
```

Set database values in `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

Start application:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed
npm run build
```

Open `http://localhost`.

For hot reload:

```bash
npm run dev
```

Stop Docker services:

```bash
./vendor/bin/sail down
```

## Option B: PHP + SQLite without Docker

```bash
git clone https://github.com/Reain2/Grace.git
cd Grace
cp .env.example .env
composer install
npm install
touch database/database.sqlite
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Open `http://localhost:8000`.

SQLite database file is local-only and ignored by Git. SQLite and MySQL use the same migrations and seeders.

## Option C: Import SQL demo dump

`database/grace_demo.sql` contains demo schema and seeded data. It does not contain `.env` values or application secrets. Passwords inside dump are demo password hashes only.

Create a MySQL database, then import:

```bash
mysql -u root -p -e "CREATE DATABASE grace CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p grace < database/grace_demo.sql
```

Configure `.env` to point to that database before starting Laravel.

Recommended path remains migrations + seed because it is portable across database versions.

## Demo accounts

All demo accounts use password `password`. Demo credentials are for local testing only.

### Superadmin

```text
superadmin@grace.test
```

### Religion admins

```text
admin.katolik@grace.test
admin.kristen-protestan@grace.test
admin.buddha@grace.test
admin.hindu@grace.test
admin.konghucu@grace.test
admin.islam@grace.test
```

### Approved users

```text
user.katolik@grace.test
user.kristen-protestan@grace.test
user.buddha@grace.test
user.hindu@grace.test
user.konghucu@grace.test
user.islam@grace.test
```

### Pending users

Use `pending.<slug>@grace.test`, for example:

```text
pending.islam@grace.test
```

### Rejected users

Use `rejected.<slug>@grace.test`, for example:

```text
rejected.islam@grace.test
```

Tradition slugs:

```text
katolik
kristen-protestan
buddha
hindu
konghucu
islam
```

All seeded names, content, proof files, and accounts are fictional. Never upload a real identity document.

## Main test flow

1. Open `/` and use landing-page login/register actions.
2. Register a new user with a dummy image.
3. Login as a pending user. Dashboard and verification status are readable; interactive features stay locked.
4. Login as the matching religion admin. Claim proof, inspect dummy proof, approve or reject.
5. Login as an approved user. Test quotes, bookmarks, reading plans, progress, streak, reminders, events, journal, calendar, and notifications.
6. Login as superadmin. Test all-tradition verification, tradition management, religion-admin management, and audit log.
7. Verify isolation: Islam admin cannot view or mutate Hindu records.

## Useful commands

### Docker

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan test
./vendor/bin/sail pint --test
./vendor/bin/sail artisan schedule:list
./vendor/bin/sail artisan reminders:send
./vendor/bin/sail artisan proofs:purge
./vendor/bin/sail down
```

### Local PHP + SQLite

```bash
php artisan migrate:fresh --seed
php artisan test
vendor/bin/pint --test
php artisan schedule:list
php artisan reminders:send
php artisan proofs:purge
```

### Frontend and dependency checks

```bash
npm run build
composer audit
```

## Stack

- Laravel 12
- PHP 8.5 runtime in Docker
- Blade, Tailwind CSS, Alpine.js
- MySQL 8.4 through Laravel Sail
- SQLite fallback for local development
- PHPUnit
- GitHub Actions

## Current features

- Six traditions: Katolik, Kristen Protestan, Buddha, Hindu, Konghucu, Islam.
- User, religion-admin, and superadmin roles.
- Registration with dummy proof and manual verification.
- Claim expiry, reject, resubmit, and tradition change re-verification.
- Tradition-isolated quotes, bookmarks, reading plans, events, and calendars.
- Reading progress, streak, one rest day, and plan version tracking.
- Reminder CRUD, timezone validation, delivery history, scheduler, database notification, and mail-log channel.
- Event RSVP, quota, waitlist, and interfaith visibility.
- Private journal.
- Database notification center.
- Audit log and admin statistics.
- Heritage UI with responsive layout, keyboard focus, skip link, and reduced-motion support.

## Security and demo limits

- Dummy proof only. Never use real KTP or identity documents.
- Demo passwords are intentionally simple and must never be used in production.
- Email uses Laravel mail log by default.
- Production email provider, official lunar holiday data, trend exports, and manual browser QA remain release tasks.
- Do not run demo seeders in production.

## Project status

Local MVP is functional and validated with automated tests on Docker/MySQL and SQLite. GitHub Actions validates Composer dependencies, migrations, Pint, PHPUnit, and frontend build.
