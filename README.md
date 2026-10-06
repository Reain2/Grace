# Grace

Platform pendamping kehidupan rohani lintas tradisi untuk demo dan testing.

## Stack

- Laravel 11
- Blade, Tailwind CSS, Alpine.js
- MySQL 8.4 melalui Laravel Sail
- PHPUnit

## Jalankan dengan Docker

```bash
cp .env.example .env
composer install
npm install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed
npm run dev
```

Buka `http://localhost`.

## Akun demo

Password semua akun demo: `password`.

- Superadmin: `superadmin@grace.test`
- Admin agama: `admin.islam@grace.test`, `admin.katolik@grace.test`, dan akun admin untuk empat tradisi lain
- User approved: `user.islam@grace.test`, `user.katolik@grace.test`, dan akun user untuk empat tradisi lain
- User pending: `pending.islam@grace.test`, dan akun pending untuk lima tradisi lain
- User rejected: `rejected.islam@grace.test`, dan akun rejected untuk lima tradisi lain

Semua akun dan konten demo bersifat fiktif. Jangan upload KTP asli. Proof hanya foto dummy untuk pengujian.

## Fitur MVP

- Enam tradisi: Katolik, Kristen Protestan, Buddha, Hindu, Konghucu, Islam.
- Registrasi dengan proof dummy dan verifikasi manual.
- Role user, admin agama, dan superadmin.
- Isolasi konten berdasarkan tradisi.
- Kutipan, bookmark, rencana bacaan, progress, dan streak.

Event, reminder email, journal, statistik, dan fitur identitas nyata belum termasuk MVP.

## Validasi

```bash
./vendor/bin/sail pint --test
./vendor/bin/sail artisan test
npm run build
```
