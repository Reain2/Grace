# Sisa Pekerjaan Grace

## MVP selesai

- Auth dan Breeze Blade.
- Docker Sail + MySQL 8.4.
- Enam tradisi dan role user/admin/superadmin.
- Registrasi proof dummy.
- Verifikasi manual, claim expiry, reject, resubmit, dan ganti tradisi.
- Quotes, bookmarks, reading plans, progress, streak, dan satu hari jeda.
- Isolasi konten berdasarkan tradisi.
- Suspend/aktifkan user oleh admin tradisi.
- Proof purge harian.
- Audit log aksi administratif dan halaman audit superadmin.
- Heritage UI dengan mode antislop DURING.
- GitHub Actions CI.
- Seed demo approved, pending, rejected untuk enam tradisi.

## Sisa sebelum rilis demo

- [ ] Jalankan GitHub Actions dan pastikan CI hijau di remote.
- [x] Automated role-access smoke test dan HTTP smoke test.
- [ ] Manual test semua akun demo pada browser mobile dan desktop.
- [ ] Review auth reset password, verify email, dan profile partial agar seluruh copy konsisten Bahasa Indonesia.
- [ ] Tambah screenshot aktual ke README.
- [ ] Review secret, `.env`, seeded password, dan deployment settings sebelum publish.
- [ ] Tambah audit log retention dan access policy lanjutan bila demo dibuka publik.

## Fitur setelah MVP

- [x] Reminder database CRUD dan timezone dasar.
- [x] Reminder delivery history dan scheduler database.
- [x] Reminder email notification channel via mail log.
- [ ] Production email provider configuration.
- [x] Event, RSVP, quota, dan waitlist.
- [x] Journal pribadi.
- [x] Kalender hari penting dasar per tradisi.
- [x] Kalender recurring tahunan dasar.
- [ ] Kalender lunar/official holiday data terverifikasi.
- [x] Statistik admin dasar.
- [ ] Statistik tren dan export.
- [x] Pengelolaan tradisi dan akun admin melalui superadmin UI.
- [x] Edit/delete reading plan.
- [x] Version tracking reading plan enrollment.
- [ ] Snapshot/versioned reading-plan content.
- [x] Edit workflow quotes.
- [x] Publish/unpublish workflow quotes.
- [x] Notification center database.
- [x] Verification and RSVP database notification producers.
- [x] Reminder delivery history and email notification.
