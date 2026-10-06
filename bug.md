# Grace Potential Bugs

Dokumen terpisah untuk mencatat potensi bug pada implementasi lokal Grace. Tidak menggantikan `MD_FILES/bug.md`.

## BUG-001 — Semua akun demo memakai password sama

**Prioritas:** High

Akun demo memakai password `password`.

**Dampak:** berbahaya jika seed demo dijalankan pada server publik.

**Perbaikan:** blokir demo seeder pada production dan gunakan password random untuk environment selain local.

## BUG-002 — Email reminder masih memakai mail log

**Prioritas:** Medium

Reminder dengan channel `email` belum mengirim ke provider email nyata.

**Dampak:** email hanya tercatat di log development.

**Perbaikan:** konfigurasi provider production, queue worker, retry, dan delivery monitoring.

## BUG-003 — Kalender annual masih menampilkan tanggal tersimpan

**Prioritas:** Medium

Holy day `is_annual` masih menampilkan tanggal dari row database, bukan next occurrence tahun berjalan.

**Dampak:** tanggal dapat terlihat salah setelah tahun berganti.

**Perbaikan:** hitung tanggal occurrence berikutnya dari bulan dan hari.

## BUG-004 — Reading plan version belum snapshot item

**Prioritas:** Medium

Enrollment menyimpan nomor version, tetapi item masih membaca data plan terbaru.

**Dampak:** perubahan item dapat memengaruhi progress user lama.

**Perbaikan:** buat tabel `reading_plan_versions` dan `reading_plan_version_items`, atau simpan snapshot item pada enrollment.

## BUG-005 — Promosi waitlist belum diuji penuh

**Prioritas:** Medium

RSVP going yang dibatalkan seharusnya mempromosikan user waitlisted, tetapi edge case belum lengkap.

**Dampak:** waitlist dapat macet atau status peserta salah.

**Perbaikan:** tambah test cancel going, promote waitlist, cancel waitlisted, duplicate cancel, dan re-RSVP.

## BUG-006 — Notification failure belum tercatat

**Prioritas:** Medium

Notification database dan email belum memiliki delivery status atau error tracking per channel.

**Dampak:** kegagalan pengiriman tidak terlihat jelas.

**Perbaikan:** tambah delivery log, retry state, dan halaman/status failed notification.

## BUG-007 — Suspend belum menginvalidasi session langsung

**Prioritas:** Medium

Suspend mengubah status user, tetapi session lama baru ditolak saat request berikutnya melewati middleware.

**Dampak:** akses aktif belum langsung dicabut.

**Perbaikan:** invalidate seluruh session user saat suspend dan cek status pada job/service.

## BUG-008 — CI belum diverifikasi di remote GitHub

**Prioritas:** Medium

Workflow CI sudah tersedia lokal, tetapi belum dijalankan pada repository remote.

**Dampak:** masalah konfigurasi GitHub Actions belum terdeteksi.

**Perbaikan:** push repository ke GitHub dan pastikan workflow hijau.

## BUG-009 — Manual browser test belum selesai

**Prioritas:** Medium

Automated tests dan HTTP smoke test belum mencakup seluruh browser mobile/desktop.

**Dampak:** bug visual, keyboard, responsive menu, upload, dan focus state masih mungkin ada.

**Perbaikan:** test enam tradisi, tiga role, approved/pending/rejected, mobile, desktop, keyboard, dan upload error.

## BUG-010 — Production deployment belum diverifikasi

**Prioritas:** Medium

Docker lokal sudah berjalan, tetapi belum ada verifikasi fresh clone, secret configuration, storage permission, worker, scheduler, dan database backup.

**Dampak:** aplikasi dapat gagal saat dipindahkan dari lokal.

**Perbaikan:** lakukan fresh setup dari clone bersih dan dokumentasikan deployment checklist.
