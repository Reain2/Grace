# Bug dan Inkonsistensi Grace

Dokumen ini mencatat masalah teknis yang dapat menjadi bug saat implementasi. Nomor baris merujuk pada dokumen saat audit dibuat.

## BUG-001 — Nama status registrasi berbeda

**Prioritas:** High
**Lokasi:** `PRD.md:97-102`, `DB_BACKEND.md:47`, `DB_BACKEND.md:192`

PRD menggunakan `pending_verification`, sedangkan ERD dan aturan backend menggunakan `pending`.

**Dampak:** middleware, enum, query, redirect, dan test dapat memakai nilai berbeda. User bisa dianggap pending oleh satu bagian tetapi tidak oleh bagian lain.

**Perbaikan:** pilih satu nilai canonical, misalnya `pending`, lalu gunakan di semua dokumen dan kode.

## BUG-002 — `reading_progress` tidak cocok dengan histori tanggal

**Prioritas:** High
**Lokasi:** `DB_BACKEND.md:107-111`, `DB_BACKEND.md:211-213`

Unique key `(user_reading_plan_id, reading_plan_item_id)` hanya mengizinkan satu row per item. Kolom `checked_on` seolah menyimpan histori, tetapi user tidak dapat mencentang ulang pada tanggal lain.

**Dampak:** streak, hari jeda, dan histori progres dapat salah.

**Perbaikan:** tetapkan model. Jika satu item hanya boleh selesai sekali, gunakan `completed_at` dan hapus konsep histori. Jika histori harian diperlukan, unique key harus memasukkan tanggal yang sesuai.

## BUG-003 — Waitlist tidak memiliki urutan pasti

**Prioritas:** High
**Lokasi:** `DB_BACKEND.md:143-148`, `DB_BACKEND.md:225-226`

`event_rsvps` tidak mencantumkan timestamp atau nomor urut waitlist.

**Dampak:** promosi peserta berikutnya dapat memilih user yang salah, terutama saat beberapa RSVP masuk hampir bersamaan.

**Perbaikan:** tambahkan `created_at` dan pilih waitlist berdasarkan waktu masuk dengan query deterministik. Pastikan index mendukung query tersebut.

## BUG-004 — Reminder dapat terkirim berulang

**Prioritas:** High
**Lokasi:** `ARCHITECTURE.md:160-164`, `DB_BACKEND.md:215-216`, `DB_BACKEND.md:361-369`

Scheduler berjalan tiap menit, tetapi tidak ada penanda bahwa reminder untuk user, rule, dan waktu tertentu sudah dikirim.

**Dampak:** retry job atau scheduler overlap dapat mengirim duplikat.

**Perbaikan:** tambahkan delivery record atau `last_sent_at` dengan idempotency key berbasis reminder dan local date/time.

## BUG-005 — Quota event nullable belum konsisten

**Prioritas:** High
**Lokasi:** `DB_BACKEND.md:129-140`, `DB_BACKEND.md:221-223`, `DB_BACKEND.md:371-379`

Aturan backend menyatakan `quota = null` berarti tanpa batas, tetapi ERD tidak menetapkan kolom nullable secara eksplisit.

**Dampak:** migration atau validation dapat menolak event tanpa batas, atau service membandingkan nilai yang tidak valid.

**Perbaikan:** tetapkan `quota` nullable dan uji kasus null, quota satu, quota penuh, dan concurrent RSVP.

## BUG-006 — Global scope dapat menyebabkan query berbeda antar konteks

**Prioritas:** High
**Lokasi:** `ARCHITECTURE.md:56-61`, `DB_BACKEND.md:347-359`

Scope membaca `auth()->user()` langsung. Query dalam command, queue, seeder, atau proses tanpa user tidak memiliki konteks yang sama.

**Dampak:** data bisa terlalu luas pada job, atau query job gagal jika logika menganggap user selalu ada.

**Perbaikan:** scope hanya menjadi filter request biasa. Untuk job dan service, kirim tradition ID secara eksplisit dan gunakan policy pada boundary akses.

## BUG-007 — Route model binding berpotensi melewati isolasi tradisi

**Prioritas:** High
**Lokasi:** `DB_BACKEND.md:294-305`, `DB_BACKEND.md:315-319`

Route seperti `/admin/verifications/{v}` dan `/admin/events/{event}` belum mendefinisikan binding atau policy enforcement.

**Dampak:** admin dapat mencoba mengakses ID milik tradisi lain jika controller hanya mengambil model berdasarkan ID.

**Perbaikan:** gunakan scoped query berdasarkan tradition ID sebelum binding atau panggil policy pada setiap resource action. Tambahkan feature test akses silang.

## BUG-008 — Claim verifikasi belum punya aturan stale claim

**Prioritas:** High
**Lokasi:** `ARCHITECTURE.md:124-132`, `DB_BACKEND.md:186-199`

Ada `claimed_by` dan `claimed_at`, tetapi tidak ada aturan kapan claim kadaluarsa atau dapat diambil alih.

**Dampak:** verifikasi dapat terkunci selamanya jika reviewer logout, crash, atau berhenti bekerja.

**Perbaikan:** tetapkan expiry claim, transaction lock, dan aturan reclaim oleh admin terkait atau superadmin.

## BUG-009 — `proof_delete_at` tidak jelas untuk rejection dan resubmit

**Prioritas:** High
**Lokasi:** `ARCHITECTURE.md:128-130`, `DB_BACKEND.md:186-200`

Dokumen menyebut purge setelah keputusan, tetapi tidak mendefinisikan apakah rejection langsung dijadwalkan, apakah resubmit membuat request baru, dan file lama kapan dihapus.

**Dampak:** file lama dapat tertinggal atau file yang sedang dibutuhkan dapat terhapus salah waktu.

**Perbaikan:** tentukan lifecycle file per verification request dan uji approve, reject, resubmit, escalation, dan purge.

## BUG-010 — Enkripsi file tidak mendukung kontrak file yang jelas

**Prioritas:** Critical
**Lokasi:** `ARCHITECTURE.md:134-145`, `DB_BACKEND.md:337-338`

Dokumen tidak menetapkan batas ukuran setelah enkripsi, format payload, streaming, error decrypt, atau key management.

**Dampak:** upload dapat gagal, file rusak, atau file tidak dapat dibaca saat review.

**Perbaikan:** untuk MVP, gunakan dummy file dan private storage tanpa menerima dokumen asli. Jika tetap memakai upload, definisikan storage encryption dan test round-trip file.

## BUG-011 — Hari raya tidak mendukung tanggal berbasis tahun

**Prioritas:** Medium
**Lokasi:** `DB_BACKEND.md:122-127`, `DB_BACKEND.md:218-219`

`holy_days.date` hanya menyimpan satu tanggal tanpa tahun atau aturan recurrence.

**Dampak:** kalender tahun berikutnya menampilkan tanggal lama atau data tidak dapat dipakai ulang.

**Perbaikan:** simpan occurrence per tahun, atau buat model recurrence yang sesuai dengan kalender tiap tradisi.

## BUG-012 — Timezone reminder belum tervalidasi penuh

**Prioritas:** Medium
**Lokasi:** `DB_BACKEND.md:173-184`, `DB_BACKEND.md:215-216`, `DB_BACKEND.md:361-369`

User memiliki timezone, tetapi aturan validasi hanya menyebut format jam. Tidak ada daftar timezone valid, DST handling, atau perilaku saat timezone berubah.

**Dampak:** reminder bisa terkirim pada waktu yang salah atau gagal diproses.

**Perbaikan:** validasi terhadap timezone identifier resmi seperti `Asia/Jakarta`, simpan waktu dalam format konsisten, dan uji timezone berbeda.

## BUG-013 — RSVP cancel dan re-RSVP belum didefinisikan

**Prioritas:** Medium
**Lokasi:** `DB_BACKEND.md:274-277`, `DB_BACKEND.md:371-379`

`updateOrCreate` dipakai untuk status RSVP, tetapi aturan rejoin setelah cancel, waitlist ulang, dan event yang sudah dimulai belum ada.

**Dampak:** user dapat memperoleh status tidak valid atau melewati urutan waitlist.

**Perbaikan:** definisikan state transition `going -> cancelled`, `waitlisted -> cancelled`, dan aturan re-RSVP.

## BUG-014 — Bookmark belum memiliki unique constraint

**Prioritas:** Medium
**Lokasi:** `DB_BACKEND.md:79-83`, `DB_BACKEND.md:266-270`

Tidak ada unique key `(user_id, quote_id)` untuk bookmark.

**Dampak:** request ganda dapat membuat bookmark duplikat.

**Perbaikan:** tambahkan unique constraint dan gunakan operasi idempotent.

## BUG-015 — Admin agama dapat kehilangan akses saat tradisi diubah

**Prioritas:** Medium
**Lokasi:** `PRD.md:55-59`, `DB_BACKEND.md:173-184`

Dokumen tidak menetapkan apakah perubahan `tradition_id` admin diperbolehkan, diaudit, atau memutus akses data lama.

**Dampak:** akses admin dapat berubah tanpa jejak atau sesi aktif tetap memiliki akses lama.

**Perbaikan:** batasi perubahan lewat superadmin, audit perubahan, invalidate session bila perlu, dan uji akses sebelum/sesudah perubahan.

## BUG-016 — Feature test tidak mencakup race condition claim dan RSVP

**Prioritas:** Medium
**Lokasi:** `ARCHITECTURE.md:193-200`, `implementation_plan.md:99-103`

Dokumen menyebut `lockForUpdate`, tetapi belum menetapkan test paralel atau transaksi bersamaan.

**Dampak:** implementasi terlihat benar pada happy path tetapi dapat melebihi quota atau memproses verification dua kali.

**Perbaikan:** tambahkan integration test untuk concurrent claim dan concurrent RSVP pada MySQL.

## BUG-017 — Data dummy berpotensi dianggap data nyata

**Prioritas:** Medium
**Lokasi:** `implementation_plan.md:4-5`, `DB_BACKEND.md:393-400`

Seeder meminta bukti dummy, akun, kutipan, dan event contoh, tetapi belum ada format penanda dummy yang terlihat di UI.

**Dampak:** demo dapat disalahpahami sebagai data nyata atau klaim produk nyata.

**Perbaikan:** gunakan label `DEMO`, domain email `.test`, dummy file yang jelas, dan README yang menyatakan seluruh data fiktif.

## BUG-018 — Tidak ada fallback saat kutipan harian kosong

**Prioritas:** Medium
**Lokasi:** `ARCHITECTURE.md:147-153`, `PRD.md:120-129`

Dokumen menyebut rotasi kutipan aktif, tetapi tidak mendefinisikan respons jika tradisi tidak memiliki kutipan aktif sama sekali.

**Dampak:** dashboard dapat error atau menampilkan widget kosong tanpa state yang jelas.

**Perbaikan:** buat empty state yang jujur dan cegah service menganggap quote selalu tersedia.

## BUG-019 — Acceptance criteria terlalu umum untuk menentukan selesai

**Prioritas:** Low
**Lokasi:** `PRD.md:155-160`, `implementation_plan.md:17-18`

Kriteria seperti “feature test lolos” dan “pengingat terkirim” belum menyebut input, output, error state, atau batas waktu.

**Dampak:** implementasi dapat dianggap selesai walau edge case inti belum bekerja.

**Perbaikan:** ubah setiap DoD menjadi skenario Given/When/Then atau test case konkret.

## BUG-020 — CI hanya direncanakan menjalankan Pest

**Prioritas:** Low
**Lokasi:** `implementation_plan.md:123-127`

Tidak ada lint, static analysis, build asset, migration test, atau pemeriksaan `.env`.

**Dampak:** error style, type, asset, dan migration dapat lolos sampai demo.

**Perbaikan:** tambahkan lint, test, asset build, migration fresh seed, dan secret check sesuai tool yang benar-benar dipakai.

## BUG-021 — Relasi reading plan dapat mencampur tradisi

**Prioritas:** Critical
**Lokasi:** `DB_BACKEND.md:84-112`, `DB_BACKEND.md:206-213`

Schema belum memastikan item berasal dari plan yang dirujuk, user mengikuti plan tradisinya, dan progress menunjuk item dari plan yang diikuti.

**Dampak:** konten tradisi dapat bocor atau progress rusak melalui ID yang valid tetapi salah relasi.

**Perbaikan:** tambahkan composite foreign key atau invariant validation pada service dan feature test.

## BUG-022 — Ganti tradisi dapat mempertahankan status approved

**Prioritas:** Critical
**Lokasi:** `PRD.md:118`, `DB_BACKEND.md:173-184`

PRD mewajibkan verifikasi ulang saat tradisi berubah, tetapi tidak ada flow untuk mencabut approval lama dan membuat request baru.

**Dampak:** user dapat langsung melihat konten tradisi baru tanpa verifikasi.

**Perbaikan:** transaction untuk perubahan tradisi, reset verification status, simpan riwayat, dan wajibkan request baru.

## BUG-023 — Response file bukti belum memiliki hardening

**Prioritas:** Critical
**Lokasi:** `ARCHITECTURE.md:134-145`, `DB_BACKEND.md:321-329`

Validasi MIME saja belum cukup. Belum ada `Cache-Control: no-store`, `X-Content-Type-Options: nosniff`, `Content-Disposition`, validasi sebelum stream, dan pemeriksaan konten berbahaya.

**Dampak:** file identitas dapat tercache, dibuka dengan content type berbahaya, atau diakses setelah authorization terlewati.

**Perbaikan:** lakukan authorization sebelum membuka file, pakai private response headers, batasi ukuran hasil decrypt, dan gunakan dummy upload untuk MVP.

## BUG-024 — Audit log dapat diubah dan menyimpan data sensitif

**Prioritas:** Critical
**Lokasi:** `DB_BACKEND.md:156-164`, `DB_BACKEND.md:231-242`

`meta` bebas JSON tanpa redaction, retention, append-only rule, atau larangan delete/update.

**Dampak:** audit trail dapat dimanipulasi dan tanpa sengaja menyimpan path bukti, email, atau data pribadi.

**Perbaikan:** whitelist metadata, redaction, retention policy, batasi update/delete, dan audit akses audit log.

## BUG-025 — Banyak verification request aktif untuk satu user

**Prioritas:** High
**Lokasi:** `PRD.md:94-119`, `DB_BACKEND.md:186-200`

Resubmit belum menentukan apakah request lama di-update atau request baru dibuat. Tidak ada invariant satu request aktif per user.

**Dampak:** reviewer dapat memproses request lama dan baru dengan hasil yang bertentangan.

**Perbaikan:** gunakan versioning request dan tetapkan satu active request; transaction saat resubmit.

## BUG-026 — Foreign key deletion behavior tidak ditentukan

**Prioritas:** High
**Lokasi:** `DB_BACKEND.md:13-164`

Tidak ada aturan `restrict`, `cascade`, atau anonymization untuk relasi database.

**Dampak:** penghapusan dapat gagal, meninggalkan orphan row, atau menghapus audit history.

**Perbaikan:** dokumentasikan behavior per foreign key. Audit log tidak boleh ikut cascade delete.

## BUG-027 — Suspension tidak memutus akses aktif dan job

**Prioritas:** High
**Lokasi:** `PRD.md:80-81`, `ARCHITECTURE.md:43-55`, `DB_BACKEND.md:304-305`

`EnsureActive` disebut tetapi efek pada session, reminder, RSVP, journal, dan queue job belum ditentukan.

**Dampak:** user suspended dapat tetap memakai session atau menerima proses yang seharusnya dihentikan.

**Perbaikan:** cek status di middleware dan service, invalidate session, dan batalkan atau skip job yang relevan.

## BUG-028 — Email verification tidak terhubung dengan aktivasi

**Prioritas:** High
**Lokasi:** `ARCHITECTURE.md:43-55`, `DB_BACKEND.md:39-50`

`email_verified_at` tersedia, tetapi approval tidak menjelaskan syarat email verified, reset password, atau expired verification link.

**Dampak:** akun dapat aktif tanpa email valid atau user tidak dapat memulihkan akses.

**Perbaikan:** tetapkan state machine email verification dan test expired link, resend, reset, serta approval.

## BUG-029 — Query event interfaith belum canonical

**Prioritas:** High
**Lokasi:** `PRD.md:64-70`, `PRD.md:120-129`, `ARCHITECTURE.md:166-170`

Belum ada aturan query eksplisit untuk event tradisi user atau event `is_interfaith`.

**Dampak:** event lintas iman dapat hilang atau event tradisi lain dapat terlihat.

**Perbaikan:** gunakan aturan `(tradition_id = user.tradition_id OR is_interfaith = true)` untuk user verified dan uji akses silang.

## BUG-030 — Queue dan scheduler belum memiliki operasi production/demo

**Prioritas:** High
**Lokasi:** `ARCHITECTURE.md:172-180`, `implementation_plan.md:119-129`, `implementation_plan.md:159-168`

Belum ada worker supervisor, retry/backoff, single scheduler, alert failed job, atau restart policy.

**Dampak:** reminder dan purge dapat berhenti tanpa diketahui.

**Perbaikan:** dokumentasikan worker, scheduler, retry, monitoring, dan failure recovery sebelum fitur async dianggap selesai.

## BUG-031 — Backup dan application key recovery belum ada

**Prioritas:** High
**Lokasi:** `ARCHITECTURE.md:134-145`, `DB_BACKEND.md:244-245`

Tidak ada RPO/RTO, restore test, backup encryption, proof exclusion, atau kebijakan pemulihan application key.

**Dampak:** data hilang atau proof terenkripsi tidak dapat dibaca setelah restore.

**Perbaikan:** tetapkan backup scope, retention, restore procedure, key custody, dan uji restore.

## BUG-032 — Privacy hanya disimpan sebagai consent timestamp

**Prioritas:** High
**Lokasi:** `PRD.md:36`, `PRD.md:109-119`, `DB_BACKEND.md:173-184`

Tidak ada privacy notice version, withdrawal consent, account deletion, export, correction, retention user data, atau breach response.

**Dampak:** lifecycle data sensitif dan hak user tidak terdefinisi.

**Perbaikan:** dokumentasikan data inventory, retention, deletion, export, consent withdrawal, dan incident response.

## BUG-033 — Reading plan dapat berubah dan merusak progress lama

**Prioritas:** Medium
**Lokasi:** `PRD.md:67-68`, `ARCHITECTURE.md:154-159`, `DB_BACKEND.md:206-213`

Admin dapat mengubah atau menghapus plan/item setelah user mulai tanpa aturan versioning.

**Dampak:** day number dan progress lama menjadi tidak cocok.

**Perbaikan:** lock published plan, buat versi baru untuk perubahan besar, atau simpan snapshot plan pada user enrollment.

## BUG-034 — Daily quote tidak deterministik di pergantian hari

**Prioritas:** Medium
**Lokasi:** `PRD.md:124`, `ARCHITECTURE.md:147-153`, `DB_BACKEND.md:69-77`

Cache memakai tanggal tradisi, tetapi timezone user dan timezone aplikasi belum ditentukan. `last_shown_at` juga merupakan shared mutable state.

**Dampak:** user dapat menerima quote hari yang salah atau hasil berubah antar request.

**Perbaikan:** pilih timezone canonical, hitung local date secara eksplisit, dan buat selection idempotent.

## BUG-035 — Settings tidak punya schema dan validasi

**Prioritas:** Medium
**Lokasi:** `PRD.md:83-90`, `DB_BACKEND.md:235-242`

`settings` belum ada pada ERD/detail schema dan tidak punya type, unique key, range, cache invalidation, atau fallback.

**Dampak:** nilai seperti retention days dapat negatif, ekstrem, atau tidak terbaca service.

**Perbaikan:** tetapkan schema key/value, tipe, allowed range, fallback, dan audit perubahan.

## BUG-036 — Design token secondary button berpotensi gagal kontras

**Prioritas:** Medium
**Lokasi:** `DESIGN.md:34-43`, `DESIGN.md:62-72`

`button-secondary` memakai tertiary `#FFE4A8` sebagai teks di background transparan. Kontrasnya belum aman di neutral atau white surface.

**Dampak:** CTA sulit dibaca dan gagal aksesibilitas.

**Perbaikan:** uji kontras semua surface; gunakan warna teks gelap atau ubah secondary button menjadi border/text dengan token yang lolos WCAG.

## BUG-037 — Demo seed belum dibatasi dari production

**Prioritas:** Medium
**Lokasi:** `DB_BACKEND.md:393-400`, `implementation_plan.md:121-129`

Belum ada visible `DEMO` label, seeded password policy, forced reset, atau guard agar seed demo tidak berjalan di production.

**Dampak:** akun dan data dummy dapat masuk ke deployment nyata.

**Perbaikan:** guard environment, domain `.test`, banner demo, random password output sekali, dan larang demo seeder di production.

## BUG-038 — Acceptance criteria belum menguji failure path

**Prioritas:** Low
**Lokasi:** `implementation_plan.md:31-50`, `implementation_plan.md:67-90`, `implementation_plan.md:94-129`

DoD dominan menguji happy path. Belum ada test failed upload, expired claim, suspended user, duplicate notification, invalid settings, deleted content, dan restore.

**Dampak:** fitur terlihat selesai tetapi gagal pada kondisi operasional.

**Perbaikan:** ubah DoD menjadi skenario failure path dan test case konkret.

## BUG-039 — Design.md belum menetapkan state dan struktur navigasi

**Prioritas:** Low
**Lokasi:** `DESIGN.md:56-132`, `PRD.md:64-90`

Design system mengatur warna, typography, radius, dan component tokens, tetapi belum menentukan page hierarchy, navigation destinations, loading/error/empty states, mobile behavior, atau focus states.

**Dampak:** implementasi UI dapat konsisten secara visual tetapi tetap tidak lengkap dan sulit dipakai.

**Perbaikan:** tetapkan struktur halaman MVP, state matrix, responsive rules, dan keyboard focus treatment sebelum membangun UI.

## Potential Bugs Saat Ini

### BUG-040 — Seeded demo password sama untuk semua akun

**Prioritas:** High

Semua akun demo memakai password `password`.

**Dampak:** aman untuk lokal saja, tetapi berbahaya jika database demo dipakai di server publik.

**Perbaikan:** blokir demo seeder pada production, gunakan password random lokal, dan tampilkan akun demo hanya pada environment non-production.

### BUG-041 — Email notification masih memakai mail log

**Prioritas:** Medium

Reminder email masuk mail log Laravel, bukan provider email nyata.

**Dampak:** user tidak menerima email di luar development.

**Perbaikan:** konfigurasi provider production, queue worker, retry, bounce handling, dan delivery monitoring.

### BUG-042 — Kalender annual belum menghitung tanggal occurrence baru

**Prioritas:** Medium

Query menampilkan item `is_annual`, tetapi view masih menampilkan tanggal tersimpan dari tahun asal.

**Dampak:** tanggal annual dapat terlihat salah setelah berganti tahun.

**Perbaikan:** hitung next occurrence berdasarkan bulan dan hari sebelum ditampilkan.

### BUG-043 — Reading plan version belum menyimpan snapshot item penuh

**Prioritas:** Medium

Enrollment menyimpan nomor version, tetapi item plan tetap memakai rows terbaru.

**Dampak:** perubahan item lama masih dapat mengubah progress user yang sudah mulai.

**Perbaikan:** simpan snapshot item pada enrollment atau buat tabel plan versions dan version items.

### BUG-044 — Event cancel belum diuji promosi waitlist

**Prioritas:** Medium

Service memiliki promosi waitlist, tetapi test saat peserta going membatalkan RSVP belum tersedia.

**Dampak:** regression pada promosi peserta berikutnya dapat lolos.

**Perbaikan:** tambah test going cancel, waitlisted promotion, duplicate cancel, dan re-RSVP.

### BUG-045 — Notification producer belum mencatat delivery failure

**Prioritas:** Medium

Notification database dan mail belum memiliki status delivery, retry result, atau error log per channel.

**Dampak:** kegagalan email tidak terlihat oleh user atau admin.

**Perbaikan:** tambahkan delivery record per channel dan failed notification monitoring.

### BUG-046 — Suspension tidak melakukan invalidasi session langsung

**Prioritas:** Medium

Admin mengubah status user menjadi suspended, tetapi session lama hanya berhenti setelah request berikutnya melewati middleware.

**Dampak:** session aktif tetap terlihat sampai request berikutnya; queue reminder juga perlu memeriksa status terbaru.

**Perbaikan:** invalidate session user saat suspend dan cek status pada setiap service/job yang mengirim tindakan.

### BUG-047 — CI belum diverifikasi pada remote GitHub

**Prioritas:** Medium

Workflow CI tersedia, tetapi repository belum memiliki remote Git atau hasil run GitHub Actions.

**Dampak:** konfigurasi YAML dapat mengandung masalah yang belum terdeteksi di remote.

**Perbaikan:** buat repository remote, push branch, lalu pastikan workflow hijau.

### BUG-048 — Manual browser test belum selesai

**Prioritas:** Medium

Automated test dan HTTP smoke test sudah ada, tetapi belum menggantikan test browser mobile/desktop.

**Dampak:** bug visual, keyboard, upload, menu mobile, dan state interaktif masih mungkin tersembunyi.

**Perbaikan:** test semua akun demo pada browser desktop dan mobile sebelum release.
