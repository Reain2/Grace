# Grace

Platform pendamping kehidupan rohani lintas tradisi untuk demo dan testing.

## Fork dan jalankan lokal

### Prasyarat

- Git
- Docker Desktop aktif
- Docker Compose tersedia melalui Docker Desktop
- Composer
- Node.js dan npm

### Setup dari fork

1. Fork repository melalui GitHub.
2. Clone fork milikmu:

```bash
git clone https://github.com/Reain2/Grace.git
cd Grace
```

3. Siapkan aplikasi:

```bash
cp .env.example .env
composer install
npm install
```

4. Jalankan Docker dan buat database demo:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed
npm run build
```

5. Buka `http://localhost`.

Landing page Grace akan menampilkan tombol masuk dan daftar. Untuk development dengan hot reload, gunakan terminal kedua:

```bash
npm run dev
```

Hentikan container:

```bash
./vendor/bin/sail down
```

Reset database dan seluruh data demo:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

## Akun demo

Password semua akun demo lokal: `password`.

- Superadmin: `superadmin@grace.test`
- Admin agama: `admin.islam@grace.test`, `admin.katolik@grace.test`, dan akun admin untuk empat tradisi lain
- User approved: `user.islam@grace.test`, `user.katolik@grace.test`, dan akun user untuk empat tradisi lain
- User pending: `pending.islam@grace.test`, dan akun pending untuk lima tradisi lain
- User rejected: `rejected.islam@grace.test`, dan akun rejected untuk lima tradisi lain

Slug tradisi:

```text
katolik
kristen-protestan
buddha
hindu
konghucu
islam
```

Semua akun dan konten demo bersifat fiktif. Jangan upload KTP asli. Proof hanya foto dummy untuk pengujian. Jangan menjalankan demo seeder pada production.

## Alur pengujian utama

1. Login sebagai `pending.islam@grace.test`.
2. Buka dashboard dan status verifikasi. Halaman dapat dilihat, tetapi interaksi terkunci.
3. Login sebagai `admin.islam@grace.test`.
4. Buka antrean verifikasi, klaim request, lihat proof dummy, lalu approve atau reject.
5. Login kembali sebagai user approved.
6. Coba quotes, bookmark, reading plan, progress, streak, reminder, event, journal, kalender, dan notifikasi.
7. Login sebagai `superadmin@grace.test` untuk audit log, kelola tradisi, admin agama, dan semua verifikasi.
8. Uji isolasi: admin Islam tidak boleh melihat atau mengubah data Hindu.

## Stack

- Laravel 12
- Blade, Tailwind CSS, Alpine.js
- MySQL 8.4 melalui Laravel Sail
- PHPUnit

## Fitur lokal

- Enam tradisi: Katolik, Kristen Protestan, Buddha, Hindu, Konghucu, Islam.
- Auth dan tiga role: user, admin agama, superadmin.
- Registrasi proof dummy dan verifikasi manual.
- Claim expiry, reject, resubmit, dan ganti tradisi.
- Isolasi konten berdasarkan tradisi.
- Quotes, bookmark, publish workflow.
- Reading plan, version tracking, progress, streak, dan satu hari jeda.
- Reminder database, delivery history, scheduler, dan mail log.
- Event, RSVP, quota, dan waitlist.
- Journal pribadi.
- Kalender hari penting tahunan dasar.
- Notification center database.
- Audit log dan statistik admin dasar.
- Heritage UI dengan responsive layout, focus state, dan reduced-motion support.

## Perintah validasi

```bash
./vendor/bin/sail pint --test
./vendor/bin/sail artisan test
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan schedule:list
npm run build
composer audit
```

## Struktur dokumentasi

- `MD_FILES/PRD.md`: kebutuhan produk.
- `MD_FILES/ARCHITECTURE.md`: rancangan arsitektur.
- `MD_FILES/DESIGN.md`: arah UI/UX.
- `MD_FILES/audit.md`: audit perencanaan.
- `MD_FILES/bug.md`: temuan teknis dari dokumen awal.
- `MD_FILES/remaining.md`: pekerjaan tersisa.
- `bug.md`: potential bugs implementasi lokal.

## Status

Project siap diuji lokal dengan Docker. GitHub Actions tersedia untuk validasi dependency, migration, Pint, PHPUnit, dan asset build. Fitur production email, kalender lunar resmi, statistik tren/export, dan manual browser QA masih di luar finishing lokal.
