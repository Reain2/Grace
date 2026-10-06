# Architecture: Grace

## 1. Gambaran Umum

Aplikasi **monolit Laravel** dengan satu codebase dan tiga area dashboard (superadmin, admin agama, user), dipisahkan lewat route group, middleware, dan layout. Data dipartisi per **tradisi** (`tradition_id`) dan dijaga lewat Policy dan *global scope*.

```
Browser
   │
   ▼
Nginx / php artisan serve
   │
   ▼
Laravel 11
 ├─ Routes      (web.php, app.php, admin.php, superadmin.php)
 ├─ Middleware  (auth, verified, active, role, verified.faith)
 ├─ Controllers (tipis)
 ├─ Form Requests
 ├─ Policies    (otorisasi + batas tradisi)
 ├─ Services    (logika bisnis)  ◄── inti aplikasi
 ├─ Models      (Eloquent + TraditionScope)
 ├─ Events / Listeners / Notifications
 └─ Jobs & Scheduler
   │
   ▼
MySQL    Storage (privat untuk bukti)    Queue (database)    Mail (log/Mailpit)
```

## 2. Stack

| Lapisan | Pilihan | Alasan |
|---|---|---|
| Framework | Laravel 11 | Standar, dokumentasi kuat |
| Auth | Laravel Breeze (Blade) | Ringan |
| UI | Blade + Tailwind + Alpine.js | Tanpa SPA |
| DB | MySQL 8 | Transaksi, index |
| Storage | Disk `local` (privat) untuk bukti verifikasi | Tidak bisa diakses via URL |
| Queue | Driver `database` | Tanpa Redis di demo |
| Test | Pest | Sintaks bersih |
| Dev env | Laravel Sail | Mudah dijalankan dari GitHub |
| CI | GitHub Actions | Test otomatis |

## 3. Pemisahan Dashboard dan Peran

| Area | Prefix | Middleware | Layout |
|---|---|---|---|
| Publik | `/` | - | `layouts.public` |
| User | `/app` | `auth, active, role:user, verified.faith` | `layouts.user` |
| Admin Agama | `/admin` | `auth, active, role:religion_admin` | `layouts.admin` |
| Superadmin | `/superadmin` | `auth, active, role:superadmin` | `layouts.superadmin` |

- Satu tabel `users` dengan kolom `role` (`superadmin` / `religion_admin` / `user`) dan `tradition_id`.
- `verified.faith` hanya meloloskan user dengan `verification_status = approved`; yang lain diarahkan ke halaman status.
- Login mengarahkan sesuai role.

### Pembatasan per tradisi

Admin agama hanya boleh menyentuh data tradisinya. Dijaga dua lapis:

1. **Global scope** `TraditionScope` pada model berkonten (Quote, ReadingPlan, Event, HolyDay, VerificationRequest): otomatis menambahkan `where tradition_id = auth()->user()->tradition_id` bila role `religion_admin`. Superadmin melewati scope.
2. **Policy**: setiap aksi memeriksa `$model->tradition_id === $user->tradition_id` atau role `superadmin`.

## 4. Struktur Folder

```
app/
├─ Enums/
│  ├─ Role.php
│  ├─ VerificationStatus.php
│  └─ ProofType.php
├─ Http/
│  ├─ Controllers/
│  │  ├─ Public/
│  │  ├─ User/        (DashboardController, ReadingController, ReminderController,
│  │  │                EventController, JournalController, VerificationStatusController)
│  │  ├─ Admin/       (QuoteController, ReadingPlanController, EventController,
│  │  │                HolyDayController, VerificationQueueController, MemberController)
│  │  └─ Superadmin/  (DashboardController, ReligionAdminController, TraditionController,
│  │                   VerificationOverviewController, SettingController, AuditLogController,
│  │                   SystemHealthController)
│  ├─ Middleware/     (EnsureRole, EnsureActive, EnsureFaithVerified)
│  └─ Requests/
├─ Models/
│  └─ Scopes/TraditionScope.php
├─ Policies/
├─ Services/
│  ├─ VerificationService.php   ← submit, claim, approve, reject, escalate
│  ├─ ProofStorageService.php   ← simpan/stream/hapus bukti
│  ├─ DailyQuoteService.php     ← pilih kutipan hari ini
│  ├─ ReadingProgressService.php← centang, streak, hari jeda
│  ├─ ReminderService.php       ← hitung & kirim pengingat
│  ├─ EventService.php          ← RSVP, kuota, daftar tunggu
│  └─ AuditLogger.php
├─ Events/ & Listeners/
├─ Notifications/
├─ Jobs/ (SendReminders, PublishDailyQuotes, PurgeProofs, EscalateStaleVerifications)
└─ Console/Commands/

routes/
├─ web.php  app.php  admin.php  superadmin.php

resources/views/
├─ layouts/ (public, user, admin, superadmin)
├─ user/  admin/  superadmin/

tests/
├─ Feature/ (RegistrationVerificationTest, TraditionIsolationTest, RoleAccessTest,
│            ReadingStreakTest, EventRsvpTest, ProofPurgeTest)
└─ Unit/    (DailyQuoteServiceTest, StreakCalculatorTest)
```

## 5. Desain Inti

### 5.1 Alur verifikasi

```
POST /register
  → RegisterRequest (data diri, tradition_id, proof_type, proof_file, consent)
  → create user (role: user, verification_status: pending)
  → ProofStorageService::store()  → disk privat, nama acak, dienkripsi
  → VerificationService::submit() → buat verification_request (status: pending)
  → event VerificationSubmitted → notifikasi ke admin tradisi

Reviewer membuka antrean:
  → VerificationService::claim()   (lock: claimed_by, claimed_at)
  → GET /admin/verifications/{id}/proof   (stream file; dicatat ke audit log)
  → approve() | reject(reason)
        approve → user.verification_status = approved, proof_delete_at = now + N hari
        reject  → attempts++ ; attempts >= 3 → status: needs_superadmin
```

Status verifikasi: `pending → in_review → approved` atau `rejected → (unggah ulang) → pending`; `needs_superadmin` setelah 3x penolakan atau eskalasi waktu.

### 5.2 Penyimpanan bukti

| Aturan | Implementasi |
|---|---|
| Tidak bisa diakses via URL | Disk `local`, file dilayani lewat controller berotorisasi |
| Enkripsi | `Crypt::encryptString` pada isi file sebelum simpan |
| Nama file | UUID acak, ekstensi dibuang |
| Akses | Hanya admin tradisi terkait dan superadmin; tiap akses dicatat |
| Penghapusan | Job `PurgeProofs` harian menghapus file lewat `proof_delete_at` |
| NIK | Tidak disimpan, tidak ada OCR |

> Untuk demo, gunakan gambar KTP **dummy**. Jangan unggah dokumen asli ke lingkungan demo.

### 5.3 Kutipan harian

`DailyQuoteService::forUser($user, $date)`:
1. Cari kutipan `scheduled_for = $date` pada tradisi user.
2. Bila tidak ada, ambil kutipan aktif yang belum ditampilkan paling lama (rotasi), sehingga tidak terulang cepat.
3. Hasil di-cache per tradisi per tanggal.

### 5.4 Rencana bacaan dan streak

- `reading_plans` berisi `reading_plan_items` (hari ke-1…n).
- User mengikuti rencana (`user_reading_plans`) dan mencentang butir (`reading_progress`).
- Streak dihitung dari hari berturut-turut dengan minimal satu centang; tersedia **1 hari jeda per minggu** yang tidak memutus streak.

### 5.5 Pengingat

- User menyimpan `reminders` (jam, hari, kanal, zona waktu).
- Scheduler menjalankan `SendReminders` tiap menit: mencari pengingat yang jatuh tempo pada zona waktu masing-masing, lalu mengirim lewat Notification (database + email).
- Pengingat hari raya dibuat dari `holy_days` (H-7 dan H-1).

### 5.6 Event dan RSVP

- `EventService::rsvp()` dalam `DB::transaction` + `lockForUpdate` pada event untuk mencegah kuota terlampaui.
- Kuota penuh → status `waitlisted`; bila ada pembatalan, peserta tunggu teratas dipromosikan.
- Event bertanda `is_interfaith` terlihat dan dapat diikuti user dari semua tradisi.

## 6. Scheduler dan Job

| Tugas | Jadwal | Fungsi |
|---|---|---|
| `SendReminders` | tiap menit | Kirim pengingat yang jatuh tempo |
| `PublishDailyQuotes` | harian 00:05 | Siapkan kutipan hari ini per tradisi |
| `PurgeProofs` | harian | Hapus file bukti sesuai `proof_delete_at` |
| `EscalateStaleVerifications` | tiap jam | Pindahkan antrean yang lewat N hari ke superadmin |
| `events:remind` | harian | Pengingat event H-1 |

## 7. Keamanan

- Policy + global scope untuk isolasi tradisi.
- Form Request untuk validasi; `$fillable` eksplisit.
- Validasi upload: MIME (jpg/png/pdf), ukuran maksimum, nama acak.
- Rate limiting pada login, registrasi, dan unggah ulang.
- Jurnal refleksi hanya dapat dibaca pemiliknya (Policy), tidak terlihat admin.
- Semua aksi admin dan setiap pembukaan bukti tercatat di `audit_logs`.
- `.env` tidak masuk repo; sediakan `.env.example`.
- Persetujuan eksplisit (checkbox) disimpan dengan timestamp.

## 8. Testing

| Jenis | Fokus |
|---|---|
| Feature | Daftar → verifikasi → akses; isolasi tradisi; RSVP kuota; streak |
| Keamanan | Admin A tidak bisa akses data tradisi B; user biasa tidak bisa akses bukti |
| Unit | `DailyQuoteService`, penghitung streak |
| Command | `PurgeProofs`, `EscalateStaleVerifications` |

## 9. Struktur Repo GitHub

```
├─ README.md
├─ docs/ (PRD.md, ARCHITECTURE.md, implementation_plan.md, DB_BACKEND.md)
├─ .github/workflows/ci.yml
├─ .env.example
└─ ... (kode Laravel)
```

Branch: `main`, `develop`, `feature/*`. Commit gaya *conventional commits*.
