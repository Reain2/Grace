# Audit Grace

## Status

Audit dilakukan terhadap dokumen perencanaan berikut:

- `PRD.md`
- `ARCHITECTURE.md`
- `DB_BACKEND.md`
- `implementation_plan.md`

Belum ada aplikasi Laravel, source code, migration, route, test, dependency manifest, atau CI. Semua temuan di bawah berasal dari desain dan rencana, bukan hasil eksekusi aplikasi.

## Prioritas

- **Critical**: risiko kebocoran data, alur inti rusak, atau keputusan arsitektur yang dapat menyebabkan data salah.
- **High**: wajib selesai sebelum demo MVP.
- **Medium**: penting, tetapi dapat ditunda setelah alur inti berjalan.
- **Low**: polish atau pengembangan lanjutan.

## Critical

### C-01. Verifikasi dokumen identitas terlalu berisiko untuk demo

Dokumen meminta upload KTP atau surat alternatif, tetapi proyek ditujukan untuk demo dan testing. Data identitas tetap sensitif walau memakai dummy data.

**Referensi:** `PRD.md:36`, `PRD.md:105-117`, `implementation_plan.md:39-50`

**Keputusan yang disarankan:** MVP memakai file dummy yang jelas, atau mengganti upload dengan metadata simulasi. Jangan menerima KTP asli.

### C-02. Model enkripsi file belum aman secara teknis

`Crypt::encryptString` disebut untuk mengenkripsi isi file. Pendekatan ini tidak menjelaskan streaming, ukuran file, rotasi key, validasi hasil decrypt, atau kegagalan pembacaan.

**Referensi:** `ARCHITECTURE.md:134-145`, `DB_BACKEND.md:337-338`

**Keputusan yang disarankan:** gunakan private storage dengan access control dan mekanisme enkripsi file yang sesuai ukuran file. Untuk demo, hilangkan upload identitas lebih aman.

### C-03. Isolasi tradisi hanya direncanakan, belum punya bukti enforcement

Global scope dapat membantu query, tetapi tidak boleh menjadi satu-satunya perlindungan. Query langsung, route binding, job, export, dan superadmin flow tetap harus diuji lewat policy.

**Referensi:** `ARCHITECTURE.md:56-61`, `DB_BACKEND.md:347-359`

**Keputusan yang disarankan:** jadikan policy dan feature test sebagai perlindungan utama; scope hanya convenience filter.

## High

### H-01. Scope MVP terlalu besar

MVP mencakup auth, verifikasi, enam tradisi, quotes, reading plans, reminder, scheduler, email, event, RSVP, journal, audit log, statistik, dan system health.

**Referensi:** `PRD.md:62-90`, `implementation_plan.md:21-129`

**Potongan MVP:** auth, role access, tradition isolation, quote, reading plan, basic approval, dan database notification sederhana.

### H-02. Status verifikasi tidak konsisten

PRD memakai `pending_verification`, sedangkan ERD dan backend memakai `pending`. Status lain juga tersebar di beberapa dokumen tanpa satu enum canonical.

**Referensi:** `PRD.md:97-102`, `DB_BACKEND.md:47`, `DB_BACKEND.md:192`

**Keputusan yang disarankan:** tetapkan satu daftar status dan gunakan nama yang sama di database, enum, middleware, route, view, dan test.

### H-03. Alur autentikasi dan akses pending belum lengkap

Dokumen menyebut user pending hanya melihat status, tetapi belum menetapkan route yang boleh diakses, redirect setelah login, logout dari status page, email verification, password reset, atau suspended user behavior.

**Referensi:** `PRD.md:109-115`, `ARCHITECTURE.md:43-55`

### H-04. Reminder belum punya desain idempotency

Scheduler tiap menit dapat mengirim reminder berulang dalam menit yang sama jika job retry atau scheduler overlap. Tidak ada `last_sent_at`, delivery key, atau tabel delivery.

**Referensi:** `ARCHITECTURE.md:160-164`, `DB_BACKEND.md:215-216`

### H-05. Waitlist belum punya urutan yang dapat diandalkan

Waitlist memerlukan urutan masuk, tetapi `event_rsvps` hanya memiliki `event_id`, `user_id`, dan `status`. Tanpa timestamp atau sequence, promosi peserta bisa tidak deterministik.

**Referensi:** `DB_BACKEND.md:143-148`, `DB_BACKEND.md:225-226`

### H-06. Testing hanya berupa daftar nama

Belum ada acceptance criteria detail, fixture, test matrix, concurrency test, atau definisi lulus untuk reminder, proof purge, claim verification, dan tradition isolation.

**Referensi:** `ARCHITECTURE.md:193-200`, `implementation_plan.md:31-32`, `implementation_plan.md:48-50`

### H-07. Sumber dan legalitas konten belum ditetapkan

Konten agama wajib bersumber jelas, tetapi belum ada format sumber, URL resmi, lisensi, proses review, atau aturan takedown.

**Referensi:** `PRD.md:127-130`, `DB_BACKEND.md:202-204`, `DB_BACKEND.md:393-400`

### H-08. Konflik roadmap

`implementation_plan.md` memasukkan event dan journal sebelum polish, sedangkan PRD menempatkan keduanya di fase kedua setelah MVP.

**Referensi:** `PRD.md:172-178`, `implementation_plan.md:94-117`

**Keputusan yang disarankan:** gunakan PRD sebagai scope produk dan buat plan baru yang mengikuti MVP nyata.

## Medium

### M-01. Enam tradisi sekaligus memperbesar beban konten dan testing

Untuk demo, dua tradisi dengan data representative cukup untuk membuktikan isolasi. Enam tradisi dapat ditambahkan lewat seeder setelah alur stabil.

**Referensi:** `PRD.md:7-10`, `DB_BACKEND.md:169-171`

### M-02. Kalender hari raya terlalu sederhana

Kolom `date` tidak menjelaskan tahun, recurrence, timezone, atau kalender lunar. Ini berisiko menghasilkan tanggal hari raya yang salah.

**Referensi:** `DB_BACKEND.md:122-127`, `DB_BACKEND.md:218-219`

### M-03. Statistik dan system health belum punya definisi metrik

Belum jelas cara menghitung partisipasi, konsistensi membaca, queue health, scheduler health, dan periode data.

**Referensi:** `PRD.md:74-90`, `implementation_plan.md:107-115`

### M-04. Tidak ada lifecycle data lengkap

Ada proof purge, tetapi belum ada aturan untuk account deletion, journal deletion, audit-log retention, notification cleanup, dan backup.

**Referensi:** `ARCHITECTURE.md:182-191`, `DB_BACKEND.md:235-245`

### M-05. Deployment terlalu bergantung pada Sail

Sail cocok untuk development, tetapi belum ada keputusan deployment production/demo host, web server, worker, scheduler, storage permission, dan mail provider.

**Referensi:** `ARCHITECTURE.md:29-40`, `implementation_plan.md:129-129`

## Low

### L-01. Dokumentasi belum memiliki README utama

Pengguna baru belum punya satu titik masuk untuk setup, akun demo, batasan data dummy, dan troubleshooting.

**Referensi:** `ARCHITECTURE.md:202-209`, `implementation_plan.md:119-129`

### L-02. Branch dan release terlalu dini

Branch strategy, tag `v1.0.0`, dan conventional commits belum penting sebelum aplikasi dan test dasar stabil.

**Referensi:** `implementation_plan.md:152-168`

## Tambahan hasil re-audit

### Critical

### C-04. Integritas lintas tradisi antar tabel belum dijaga

Schema belum mencegah reading plan item dipasang ke plan lain, user mengikuti plan tradisi lain, atau progress menunjuk item yang tidak termasuk plan yang diikuti.

**Referensi:** `DB_BACKEND.md:84-112`, `DB_BACKEND.md:206-213`

**Keputusan yang disarankan:** gunakan composite foreign key atau validasi service dengan invariant test untuk semua hubungan lintas tradisi.

### C-05. Perubahan tradisi user belum punya lifecycle

PRD mewajibkan verifikasi ulang saat tradisi berubah, tetapi tidak menjelaskan route, status lama, revocation, riwayat perubahan, atau transaksi perubahan.

**Referensi:** `PRD.md:118`, `DB_BACKEND.md:173-184`

### C-06. Keamanan akses file bukti belum lengkap

Belum ada aturan response `Cache-Control: no-store`, `X-Content-Type-Options: nosniff`, `Content-Disposition`, authorization sebelum membuka file, dan pemeriksaan konten berbahaya.

**Referensi:** `PRD.md:105-117`, `ARCHITECTURE.md:134-145`, `DB_BACKEND.md:321-329`

### C-07. Audit log dapat menyimpan data sensitif dan belum tamper-resistant

`meta` adalah JSON bebas tanpa redaction, retention, append-only rule, atau perlindungan perubahan dan penghapusan.

**Referensi:** `ARCHITECTURE.md:182-191`, `DB_BACKEND.md:156-164`, `DB_BACKEND.md:231-242`

### High

### H-09. Verification request aktif tidak dibatasi

Belum ada aturan satu request aktif per user. Resubmit dapat menghasilkan beberapa request pending atau beberapa request approved.

**Referensi:** `PRD.md:94-119`, `DB_BACKEND.md:186-200`

### H-10. Foreign key deletion behavior belum ditentukan

Tidak ada keputusan `restrict`, `cascade`, atau anonymization untuk user, tradition, event, quote, plan, verification request, dan audit log.

**Referensi:** `DB_BACKEND.md:13-164`

### H-11. Suspension belum mencakup session dan job

Belum jelas apakah user suspended langsung logout, berhenti menerima reminder, tidak dapat RSVP, atau tidak dapat menulis journal melalui job/form yang sedang berjalan.

**Referensi:** `PRD.md:80-81`, `ARCHITECTURE.md:43-55`, `DB_BACKEND.md:304-305`

### H-12. Email verification belum terhubung dengan approval

`email_verified_at` ada di schema, tetapi approval tidak menjelaskan apakah email harus verified sebelum akun aktif, bagaimana reset password, atau bagaimana link kedaluwarsa ditangani.

**Referensi:** `ARCHITECTURE.md:43-55`, `DB_BACKEND.md:39-50`

### H-13. Event lintas iman belum punya query rule canonical

Dokumen menyebut event tradisi user dan event lintas iman, tetapi belum menetapkan filter pasti: tradisi user atau `is_interfaith = true`.

**Referensi:** `PRD.md:64-70`, `PRD.md:120-129`, `ARCHITECTURE.md:166-170`

### H-14. Operasional queue dan scheduler belum deployable

Belum ada worker supervisor, retry/backoff, single scheduler, alert job gagal, queue growth monitoring, atau restart policy.

**Referensi:** `ARCHITECTURE.md:172-180`, `implementation_plan.md:119-129`, `implementation_plan.md:159-168`

### H-15. Backup, restore, dan key management belum ada

Belum ada RPO/RTO, backup terenkripsi, restore test, aturan mengecualikan proof, atau pemulihan application key.

**Referensi:** `ARCHITECTURE.md:134-145`, `DB_BACKEND.md:244-245`

### H-16. Privacy lifecycle berhenti pada consent timestamp

Belum ada privacy notice version, withdrawal consent, account deletion, export data, correction, retention user data, atau breach response.

**Referensi:** `PRD.md:36`, `PRD.md:109-119`, `DB_BACKEND.md:173-184`

### Medium

### M-06. Reading plan dapat berubah setelah user mulai

Belum ada aturan edit/delete plan dan item yang sudah memiliki progress.

**Referensi:** `PRD.md:67-68`, `ARCHITECTURE.md:154-159`, `DB_BACKEND.md:206-213`

### M-07. Daily quote belum deterministik terhadap timezone

Tanggal user dan tanggal aplikasi belum dibedakan. Cache per tradisi dan tanggal dapat memberi hasil berbeda di sekitar pergantian hari.

**Referensi:** `PRD.md:124`, `ARCHITECTURE.md:147-153`, `DB_BACKEND.md:69-77`

### M-08. Settings belum punya schema operasional

`settings` belum ada di ERD, dan belum ada tipe value, unique key, range validation, cache invalidation, atau fallback.

**Referensi:** `PRD.md:83-90`, `DB_BACKEND.md:235-242`

### M-09. Security controls belum punya nilai konkret

Rate limit disebut, tetapi limit, key, durasi, response, CSRF, session security, dan authorization failure behavior belum ditetapkan.

**Referensi:** `ARCHITECTURE.md:182-191`, `DB_BACKEND.md:281-319`

### M-10. Design system baru tersedia, tetapi belum dipetakan ke scope produk

`DESIGN.md` memberi token visual, namun belum mendefinisikan struktur halaman, navigation destination, state kosong/loading/error, responsive behavior, focus state, dan komponen untuk tiga dashboard.

**Referensi:** `DESIGN.md:56-132`, `PRD.md:64-90`

### M-11. Design token memiliki potensi konflik warna

`button-secondary` memakai `textColor: {colors.tertiary}` pada background transparan. Kontras dan keterbacaan pada warm neutral atau white surface belum dibuktikan.

**Referensi:** `DESIGN.md:34-43`, `DESIGN.md:62-72`

### M-12. Demo seed belum cukup aman

Belum ada visible `DEMO` label, kebijakan password seeded account, reset password, atau pencegahan deploy seed ke production.

**Referensi:** `DB_BACKEND.md:393-400`, `implementation_plan.md:121-129`

## Urutan perbaikan yang disarankan

1. Tetapkan MVP dan status verifikasi canonical.
2. Hapus atau simulasi upload dokumen identitas.
3. Tetapkan constraint lintas tradisi dan lifecycle verification.
4. Bangun auth, role access, dan tradition isolation.
5. Gunakan `DESIGN.md` saat membangun layout, state, dan responsive UI.
6. Tambahkan quote dan reading plan.
7. Tambahkan test untuk akses, isolasi, verifikasi, dan progres.
8. Tambahkan reminder database tanpa email lebih dulu.
9. Baru pertimbangkan event, journal, email, audit UI, dan system health.
