# PRD: Grace

> Platform pendamping kehidupan rohani lintas tradisi: kegiatan keagamaan, event penguat iman, pengingat membaca, dan kutipan harian.

| | |
|---|---|
| Versi | 0.1 (draft, untuk demo & testing) |
| Stack | Laravel 11, MySQL, Blade + Tailwind |
| Tradisi | Kristen Protestan, Kristen Katolik, Buddha, Hindu, Konghucu, Islam |
| Peran | Superadmin, Admin Agama, User |

---

## 1. Latar Belakang

Banyak orang ingin lebih konsisten dalam kehidupan rohani (rutin membaca, ikut kegiatan, mengingat hari raya), tetapi informasinya tersebar di grup chat, media sosial, dan pengumuman rumah ibadah. Aplikasi yang ada umumnya hanya melayani satu agama.

Grace menyatukan kebutuhan itu dalam satu platform, dengan **konten, istilah, dan kalender yang dipisah per tradisi**, dikelola oleh admin dari tradisi masing-masing.

## 2. Tujuan

1. User mendapat kutipan harian, rencana bacaan, dan pengingat sesuai tradisinya.
2. User mudah menemukan dan mengikuti kegiatan serta event keagamaan.
3. Konten tiap tradisi dikelola oleh admin tradisi itu sendiri.
4. Pendaftaran terverifikasi, sehingga komunitas tiap tradisi terjaga.
5. Menjadi proyek demo yang rapi untuk portofolio GitHub.

## 3. Non-Goals (di luar scope)

- Pembayaran/donasi.
- Streaming ibadah (live video).
- Chat atau forum real-time.
- Aplikasi mobile native.
- Penyediaan teks kitab suci lengkap di dalam aplikasi.

> Catatan: proyek ini untuk **demo dan testing**. Data identitas yang dipakai harus berupa **data contoh (dummy)**, bukan KTP asli.

## 4. Persona

| Persona | Kebutuhan |
|---|---|
| **Jemaat/umat aktif** | Pengingat membaca, kutipan harian, info event |
| **Pemula yang ingin lebih konsisten** | Rencana bacaan ringan, streak tanpa tekanan |
| **Admin agama (pengurus/pemuka)** | Mengelola konten dan event tradisinya, memverifikasi pendaftar |
| **Superadmin (pengelola sistem)** | Mengelola akun admin, pengaturan, dan kesehatan sistem |

## 5. Peran & Hak Akses

| Fitur | User | Admin Agama | Superadmin |
|---|:-:|:-:|:-:|
| Registrasi + unggah bukti | ✅ | - | - |
| Lihat kutipan, bacaan, event tradisinya | ✅ | ✅ | ✅ |
| Rencana bacaan, pengingat, streak, jurnal | ✅ | ❌ | ❌ |
| RSVP event, simpan agenda | ✅ | ❌ | ❌ |
| Kelola kutipan, rencana bacaan, event, hari raya | ❌ | ✅ (tradisinya saja) | ✅ (semua) |
| Verifikasi pendaftar | ❌ | ✅ (tradisinya saja) | ✅ (semua) |
| Moderasi user tradisinya | ❌ | ✅ | ✅ |
| Kelola akun admin agama | ❌ | ❌ | ✅ |
| Kelola daftar tradisi & pengaturan sistem | ❌ | ❌ | ✅ |
| Audit log & kesehatan sistem | ❌ | ❌ | ✅ |

## 6. Fitur

### 6.1 Dashboard User (`/app`)
- **Beranda**: kutipan hari ini, rencana bacaan aktif, event mendatang, streak.
- **Kutipan Harian**: sesuai tradisi; bisa di-bookmark.
- **Rencana Bacaan**: pilih rencana, centang progres harian, lihat persentase dan streak.
- **Pengingat**: atur jam dan kanal (email dan notifikasi di web) untuk membaca/berdoa/meditasi.
- **Event & Kegiatan**: daftar, filter, detail, RSVP, simpan ke agenda.
- **Kalender Hari Raya**: hari penting tradisinya, dengan pengingat menjelang hari.
- **Jurnal Refleksi**: catatan pribadi (rasa syukur, renungan); hanya terlihat oleh pemilik.
- **Profil & Status Verifikasi**.

### 6.2 Dashboard Admin Agama (`/admin`)
- **Ringkasan**: user terverifikasi, pendaftar menunggu, event mendatang, partisipasi.
- **Antrean Verifikasi**: tinjau bukti dan putuskan.
- **Kutipan**: CRUD, jadwal terbit harian.
- **Rencana Bacaan**: CRUD rencana beserta butir harian.
- **Event**: CRUD, kuota, RSVP, daftar peserta.
- **Kalender Hari Raya**: CRUD.
- **User**: daftar user tradisinya, suspend.

### 6.3 Dashboard Superadmin (`/superadmin`)
- **Ringkasan sistem**: total user per tradisi, antrean verifikasi, job gagal.
- **Admin Agama**: buat/nonaktifkan akun, tetapkan tradisi.
- **Tradisi**: kelola daftar tradisi.
- **Verifikasi (semua)**: lihat antrean semua tradisi, ambil alih bila macet.
- **Pengaturan**: masa simpan bukti, batas percobaan, zona waktu bawaan.
- **Audit Log**.
- **Kesehatan sistem**: status queue, scheduler, dan job gagal.

## 7. Registrasi & Verifikasi

### 7.1 Alur
```
Daftar (data diri + pilih tradisi + unggah bukti + centang persetujuan)
  → status: pending_verification
  → masuk antrean admin tradisi yang dipilih (superadmin melihat semua)
  → reviewer klaim (lock) lalu memeriksa bukti
       ├─ sesuai → approved → akun aktif, notifikasi
       └─ tidak sesuai/buram → rejected + alasan → user boleh unggah ulang
  → bukti dihapus otomatis setelah masa simpan
```

### 7.2 Jenis bukti
1. **KTP** (kolom agama harus sesuai tradisi yang dipilih).
2. **Surat alternatif**, jika KTP belum diperbarui atau pindah agama: surat baptis, surat keterangan dari pemuka/rumah ibadah, atau dokumen setara.

### 7.3 Aturan verifikasi
1. Akun `pending_verification` hanya bisa melihat halaman status; fitur lain terkunci.
2. Antrean tradisi X hanya terlihat oleh admin tradisi X dan superadmin.
3. Reviewer **mengklaim** item agar tidak diperiksa dua orang.
4. Penolakan wajib menyertakan alasan; maksimal **3 kali percobaan**, setelahnya perlu ditinjau superadmin.
5. Item yang tidak diproses dalam **N hari** (default 3) otomatis naik ke antrean superadmin.
6. File bukti **dihapus otomatis** N hari setelah keputusan (default 7). Yang disimpan hanya hasil: status, reviewer, waktu.
7. Aplikasi **tidak menyimpan NIK** dan tidak menjalankan OCR.
8. Setiap pembukaan file bukti dicatat di audit log.
9. Ganti tradisi mewajibkan verifikasi ulang.

## 8. Aturan Bisnis Lain

1. User terhubung ke **satu tradisi utama** (MVP); konten lintas tradisi hanya pada event bertanda "lintas iman".
2. Event lintas iman dapat diikuti user terverifikasi dari tradisi mana pun.
3. Kutipan harian dipilih dari kutipan terjadwal tradisi user; bila kosong, dipilih acak dari kutipan aktif.
4. Streak naik bila minimal satu butir bacaan hari itu dicentang. Melewatkan satu hari tidak menghapus riwayat (ada "hari jeda" 1x per minggu).
5. Pengingat dikirim sesuai zona waktu user (WIB/WITA/WIT).
6. Event penuh kuota masuk daftar tunggu.
7. Admin agama tidak dapat mengakses data tradisi lain (diuji otomatis).
8. Konten wajib mencantumkan **sumber** (kitab/penulis/terjemahan) agar jelas asalnya.

## 9. Alur Utama User

```
Daftar → terverifikasi → pilih rencana bacaan & atur pengingat
→ tiap hari: terima kutipan + pengingat → centang bacaan → streak naik
→ jelajah event → RSVP → hadir → jurnal refleksi
```

## 10. User Stories (MVP)

**User**
- Sebagai user, saya ingin kutipan harian sesuai tradisi saya agar terinspirasi tiap hari.
- Sebagai user, saya ingin pengingat membaca agar lebih konsisten.
- Sebagai user, saya ingin mendaftar event dan menyimpannya di agenda.
- Sebagai user, saya ingin menulis jurnal pribadi yang tidak terlihat orang lain.

**Admin Agama**
- Sebagai admin agama, saya ingin mengelola konten tradisi saya agar akurat.
- Sebagai admin agama, saya ingin memverifikasi pendaftar agar komunitas terjaga.

**Superadmin**
- Sebagai superadmin, saya ingin mengelola akun admin agama dan melihat kesehatan sistem.
- Sebagai superadmin, saya ingin mengambil alih verifikasi yang macet.

## 11. Kriteria Keberhasilan

- Alur daftar → verifikasi → pakai fitur lolos feature test.
- Admin tradisi A tidak bisa melihat/mengubah data tradisi B (test otomatis).
- Pengingat terkirim sesuai jadwal pada data demo.
- File bukti terhapus otomatis sesuai pengaturan.

## 12. Risiko

| Risiko | Mitigasi |
|---|---|
| Kebocoran dokumen identitas | Disk privat terenkripsi, tanpa NIK, hapus otomatis, audit log; demo hanya pakai data dummy |
| Konten keliru atau tidak sesuai ajaran | Dikelola admin tradisi, wajib mencantumkan sumber |
| Hak cipta terjemahan kitab | Pakai sumber domain publik/berlisensi atau tautan ke sumber resmi |
| Antrean verifikasi menumpuk | Eskalasi otomatis ke superadmin |
| Nada yang tidak netral | Panduan penulisan konten, antarmuka netral |

## 13. Roadmap

| Fase | Isi |
|---|---|
| **1 (MVP)** | Auth, 3 role, registrasi + verifikasi, kutipan harian, rencana bacaan, pengingat |
| **2** | Event + RSVP, kalender hari raya, jurnal, notifikasi |
| **3** | Event lintas iman, statistik, ekspor agenda (iCal), multi-tradisi per user |
