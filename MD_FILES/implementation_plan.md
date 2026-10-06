# Implementation Plan: Grace

Rencana pengerjaan bertahap sampai siap di-upload ke GitHub. Setiap fase punya tujuan, tugas, dan *definition of done* (DoD).

**Estimasi total:** ±4 minggu (paruh waktu). Proyek ini untuk **demo dan testing**, jadi gunakan data dummy (termasuk gambar KTP contoh).

---

## Fase 0: Persiapan (0,5 hari)

- [ ] Buat repo GitHub `grace` (`.gitignore` Laravel, lisensi)
- [ ] `composer create-project laravel/laravel grace`
- [ ] Setup Laravel Sail (MySQL)
- [ ] Salin dokumen ke `docs/`
- [ ] Commit pertama: `chore: initial laravel setup`

**DoD:** `./vendor/bin/sail up` berjalan dan halaman welcome tampil.

---

## Fase 1: Auth, 3 Role, dan Kerangka Dashboard (2 hari)

- [ ] Install Breeze (Blade)
- [ ] Migration: tambah `role`, `status`, `tradition_id`, `verification_status`, `timezone` ke `users`
- [ ] Migration + seeder `traditions` (6 tradisi)
- [ ] Enum `Role`, `VerificationStatus`
- [ ] Middleware `EnsureRole`, `EnsureActive`, `EnsureFaithVerified`
- [ ] Route terpisah: `app.php`, `admin.php`, `superadmin.php`
- [ ] Empat layout: `public`, `user`, `admin`, `superadmin`
- [ ] Seeder: 1 superadmin, 1 admin per tradisi (6), beberapa user contoh
- [ ] Test: `RoleAccessTest`

**DoD:** tiap role masuk ke dashboard sendiri; akses silang menghasilkan 403.

---

## Fase 2: Registrasi dan Verifikasi (4 hari)

- [ ] Migration + model `verification_requests`
- [ ] Form registrasi: data diri, pilih tradisi, jenis bukti (KTP/surat), unggah file, persetujuan
- [ ] `ProofStorageService`: simpan terenkripsi di disk privat, nama acak
- [ ] `VerificationService`: `submit`, `claim`, `approve`, `reject`, `escalate`
- [ ] Halaman status verifikasi untuk user (`pending`, `rejected` + alasan, unggah ulang)
- [ ] Antrean verifikasi admin agama (hanya tradisinya) dan superadmin (semua)
- [ ] Route aman untuk melihat bukti + catat audit log
- [ ] Batas 3 percobaan → `needs_superadmin`
- [ ] Job `PurgeProofs` dan `EscalateStaleVerifications`
- [ ] Test: `RegistrationVerificationTest`, `ProofPurgeTest`

**DoD:** user baru terkunci sampai disetujui; admin tradisi lain tidak melihat antrean yang bukan miliknya; bukti terhapus otomatis.

---

## Fase 3: Isolasi Tradisi dan Konten Dasar (3 hari)

- [ ] `TraditionScope` dan trait `BelongsToTradition`
- [ ] Migration + model: `quotes`, `holy_days`
- [ ] CRUD admin agama: kutipan (dengan jadwal terbit dan sumber wajib), hari raya
- [ ] CRUD superadmin: admin agama, tradisi
- [ ] Policy per model
- [ ] Test: `TraditionIsolationTest` (admin A tidak bisa lihat/ubah data B)

**DoD:** admin hanya mengelola konten tradisinya; superadmin mengelola semuanya.

---

## Fase 4: Kutipan Harian dan Rencana Bacaan (3 hari)

- [ ] `DailyQuoteService` + unit test
- [ ] Widget kutipan hari ini + bookmark
- [ ] Migration + model: `reading_plans`, `reading_plan_items`, `user_reading_plans`, `reading_progress`
- [ ] CRUD rencana bacaan di admin agama
- [ ] Halaman user: pilih rencana, centang harian, persentase progres
- [ ] `ReadingProgressService` + hitung streak + hari jeda; unit test
- [ ] Test: `ReadingStreakTest`

**DoD:** user melihat kutipan sesuai tradisi dan streak naik saat membaca.

---

## Fase 5: Pengingat (2 hari)

- [ ] Migration + model `reminders`
- [ ] Halaman atur pengingat (jam, hari, kanal, zona waktu)
- [ ] `ReminderService` + Job `SendReminders` (scheduler tiap menit)
- [ ] Notifikasi database + email (Mailpit)
- [ ] Pengingat hari raya (H-7, H-1) dari `holy_days`
- [ ] Test: pengingat terkirim tepat waktu pada zona waktu berbeda

**DoD:** pengingat tiba sesuai jadwal dan zona waktu user.

---

## Fase 6: Event dan RSVP (3 hari)

- [ ] Migration + model: `events`, `event_rsvps`
- [ ] CRUD event di admin agama (kuota, tanggal, lokasi/tautan, flag `is_interfaith`)
- [ ] Halaman user: jelajah, filter, detail, RSVP, batalkan
- [ ] `EventService`: kuota dengan `lockForUpdate`, daftar tunggu, promosi otomatis
- [ ] Tombol simpan ke agenda
- [ ] Test: `EventRsvpTest` (kuota tidak terlampaui, daftar tunggu)

**DoD:** RSVP aman dari race condition; event lintas iman terlihat oleh semua tradisi.

---

## Fase 7: Jurnal, Statistik, dan Superadmin Lengkap (2,5 hari)

- [ ] Migration + model `journal_entries`; Policy hanya pemilik
- [ ] Statistik admin agama: user terverifikasi, partisipasi event, konsistensi membaca
- [ ] Dashboard superadmin: user per tradisi, antrean verifikasi, kesehatan sistem (queue, job gagal)
- [ ] Halaman audit log dan pengaturan (`settings`)
- [ ] Test: admin tidak bisa membaca jurnal user

**DoD:** tiap dashboard menampilkan data yang relevan sesuai peran.

---

## Fase 8: Polish dan Rilis GitHub (2 hari)

- [ ] Rapikan UI (empty state, pesan error, responsif)
- [ ] Seeder demo lengkap: kutipan dan rencana bacaan contoh tiap tradisi, event, hari raya
- [ ] README: deskripsi, fitur, screenshot, cara install, akun demo per role
- [ ] **Catatan di README**: sumber konten, dan bahwa proyek ini hanya untuk demo (gunakan data dummy)
- [ ] GitHub Actions: jalankan Pest
- [ ] Pastikan `.env` tidak ter-commit
- [ ] Tag rilis `v1.0.0`

**DoD:** orang lain bisa clone, `sail up`, `migrate --seed`, lalu mencoba semua role.

---

## Ringkasan Jadwal

| Fase | Fokus | Estimasi |
|---|---|---|
| 0 | Persiapan | 0,5 hari |
| 1 | Auth, 3 role, layout | 2 hari |
| 2 | Registrasi + verifikasi | 4 hari |
| 3 | Isolasi tradisi + konten dasar | 3 hari |
| 4 | Kutipan harian + rencana bacaan | 3 hari |
| 5 | Pengingat | 2 hari |
| 6 | Event + RSVP | 3 hari |
| 7 | Jurnal, statistik, superadmin | 2,5 hari |
| 8 | Polish + rilis | 2 hari |
| | **Total** | **±22 hari kerja** |

**Jalur MVP tercepat:** Fase 0 → 1 → 2 → 3 → 4 → 5 (±14,5 hari). Event dan jurnal bisa menyusul.

---

## Konvensi Kerja

- **Branch:** `main`, `develop`, `feature/<nama>`.
- **Commit:** `feat:`, `fix:`, `test:`, `docs:`, `chore:`.
- **Aturan emas:** logika di Service, validasi di Form Request, otorisasi di Policy, isolasi tradisi lewat scope.
- **Sebelum merge:** `./vendor/bin/sail test` harus hijau.

## Perintah Berguna

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail artisan make:model Quote -mfs
./vendor/bin/sail artisan make:policy QuotePolicy --model=Quote
./vendor/bin/sail artisan schedule:work
./vendor/bin/sail artisan queue:work
./vendor/bin/sail test
```
