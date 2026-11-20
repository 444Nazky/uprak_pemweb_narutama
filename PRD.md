# Product Design Requirements (PRD)
**Nama Proyek:** Sistem Kotak Aspirasi Sekolah (Fase Akhir / Production Ready)  
**Platform / Tech Stack:** Web Application — Laravel (MVC), Blade Templating, Bootstrap 5 / Tailwind CSS, MySQL  
**Target Pengguna:** Tamu (Publik), Siswa, dan Admin (Pihak Sekolah)  
**Konteks:** Ujian Praktik Kejuruan Pemrograman Web — Kelas XI RPL (SMK Plus Pelita Nusantara Bogor)  
**Versi Dokumen:** 1.0  

---

## 1. Ringkasan Produk (Product Overview)
**Sistem Kotak Aspirasi Sekolah** adalah platform web resmi sekolah yang berfungsi untuk menampung laporan sarana-prasarana serta masukan dari siswa secara digital, terstruktur, dan aman. Pada pengembangan fase akhir (*Production Ready*) ini, sistem bertransformasi dari prototipe dasar menjadi aplikasi multi-peran (*role-based*) yang dilengkapi sistem autentikasi, proteksi rute via *Middleware*, manajemen kategori, unggahan foto bukti, pembaruan status penanganan, tanggapan resmi admin, serta perlindungan privasi pelapor.

---

## 2. Tujuan & Masalah yang Diselesaikan (Goals & Problem Statement)
| Masalah pada Versi Dasar | Solusi pada Fase Akhir (Production Ready) |
| :--- | :--- |
| Siapa saja bisa mengisi form tanpa identitas jelas & semua siswa bisa melihat laporan orang lain. | Implementasi sistem akun (`users`), kolom `kelas` & `role`, serta pembatasan kepemilikan data (siswa hanya bisa melihat aspirasinya sendiri). |
| Laporan tidak memiliki kategori, foto bukti, maupun status penanganan. | Penambahan relasi tabel `kategoris`, fitur upload `foto` (maks. 2 MB), serta alur status (`menunggu` $\rightarrow$ `diproses` $\rightarrow$ `selesai`). |
| Belum ada validasi server-side dan pihak sekolah belum bisa membalas laporan. | Penerapan validasi `$request->validate()`, form tanggapan admin (`PUT/PATCH`), serta proteksi akses admin dengan `AdminMiddleware`. |

---

## 3. Peran Pengguna & Matriks Hak Akses (User Roles & Permissions)

1. **Tamu (Guest / Unauthenticated):**
   * Mengakses halaman Beranda (`/`) untuk melihat ringkasan statistik aspirasi (tanpa bisa membaca isi atau identitas laporan siapa pun).
   * Mengakses halaman `/login` dan `/register`.
   * Otomatis diarahkan ke `/login` jika mencoba mengakses rute siswa atau admin.

2. **Siswa (`role: siswa`):**
   * Mendaftar mandiri melalui halaman Register (otomatis mendapat peran `siswa`).
   * Mengirim aspirasi baru lengkap dengan kategori, lokasi, isi laporan, dan foto bukti opsional.
   * Melihat daftar aspirasi miliknya sendiri (`/aspirasi-saya`) dan detailnya (`/aspirasi/{id}`).
   * Ditolak aksesnya (HTTP `403` atau redirect) jika mencoba membuka detail aspirasi milik siswa lain atau rute `/admin/*`.

3. **Admin (`role: admin`):**
   * Akun dibuat eksklusif melalui `AdminSeeder` (tidak tersedia di form Register).
   * Melihat statistik real-time pada `/admin/dashboard`.
   * Melihat seluruh daftar aspirasi beserta identitas pelapor (Nama & Kelas), memfilter data berdasarkan status/kategori, mengubah status, menulis tanggapan, dan menghapus aspirasi.

---

## 4. Kebutuhan Fungsional (Functional Requirements)

### 4.1. Modul Autentikasi & Navigasi Dinamis
* **Register & Login:** Menggunakan Laravel UI (Bootstrap) atau Laravel Breeze. Form Register wajib menyertakan input `name`, `email`, `kelas`, dan `password`.
* **Navbar Dinamis (Terpusat di Master Layout):**
  * **Tamu:** Menampilkan tombol `Login` dan `Register`.
  * **Siswa:** Menampilkan sapaan `"Halo, [Nama]"`, menu `Kirim Aspirasi`, `Aspirasi Saya`, dan tombol `Logout`.
  * **Admin:** Menampilkan sapaan `"Halo, [Nama]"`, menu `Dashboard`, `Kelola Aspirasi`, dan tombol `Logout`.
* **Keamanan Logout:** Tombol Logout wajib menggunakan `<form method="POST">` yang dilengkapi token `@csrf`.

### 4.2. Modul Keamanan & Middleware
* **Middleware `auth`:** Melindungi seluruh endpoint siswa dan admin dari pengunjung yang belum login.
* **Custom `AdminMiddleware` (alias `admin`):** Terdaftar di `bootstrap/app.php` dan membungkus grup rute `Route::middleware(['auth', 'admin'])->prefix('admin')`. Menolak akses pengguna dengan `role !== 'admin'`.
* **Validasi Kepemilikan Data (Anti-IDOR):** Pada endpoint `GET /aspirasi/{id}`, Controller wajib memeriksa kesesuaian `user_id` pemilik aspirasi dengan ID pengguna yang sedang login (`Auth::id()`).

### 4.3. Modul Aspirasi Siswa
* **Form Kirim Aspirasi (`GET /aspirasi/create` & `POST /aspirasi`):**
  * `judul` (String, wajib).
  * `kategori_id` (Dropdown relasi tabel `kategoris`; minimal 4 kategori: *Sarana & Prasarana*, *Kebersihan*, *Kegiatan Belajar*, *Lain-lain*).
  * `lokasi` (String, wajib).
  * `pesan` (Text, wajib).
  * `foto` (File opsional, format `JPG/PNG`, ukuran maksimal `2048 KB`, disimpan di `storage/app/public/bukti` dan diakses via `php artisan storage:link`).
* **Validasi & Feedback:** Validasi sisi server wajib mengembalikan pesan error ke form dan mempertahankan input sebelumnya menggunakan helper `old()`. Jika berhasil, status awal diset ke `menunggu` dan redirect disertai *flash message* sukses.
* **Daftar Aspirasi Saya (`GET /aspirasi-saya`):** Menampilkan aspirasi milik user aktif, diurutkan dari yang terbaru, dilengkapi *badge* status, pratinjau tanggapan admin, dan *pagination*.
* **Detail Aspirasi (`GET /aspirasi/{id}`):** Menampilkan foto ukuran penuh (jika ada), judul, kategori, lokasi, tanggal, *badge* status, isi laporan, dan tanggapan admin.

### 4.4. Modul Panel Admin
* **Dashboard Statistik (`GET /admin/dashboard`):** Menampilkan 4 kartu metrik (**Total**, **Menunggu**, **Diproses**, **Selesai**) yang angkanya diambil langsung dari query Eloquent database.
* **Manajemen Daftar Aspirasi (`GET /admin/aspirasi`):** Tabel seluruh laporan dilengkapi fitur **filter berdasarkan status & kategori** serta *pagination*.
* **Detail & Tanggapan (`GET /admin/aspirasi/{id}` & `PUT/PATCH /admin/aspirasi/{id}`):** Menampilkan identitas pelapor (`name` dan `kelas`), serta form untuk memperbarui `status` (`menunggu` $\rightarrow$ `diproses` $\rightarrow$ `selesai`) sekaligus mengisi `tanggapan`.
* **Penghapusan Data (`DELETE /admin/aspirasi/{id}`):** Menggunakan tombol warna merah (`btn-danger`) dan wajib memunculkan konfirmasi sebelum data dihapus.

### 4.5. Modul Pengembangan Mandiri (Minimal 2 Fitur / Maksimal 10 Poin)
Pengembang wajib memilih dan mengimplementasikan **minimal 2 fitur tambahan** dari daftar berikut:
* **Tingkat Mudah (2 Poin/fitur):**
  1. Pencarian kata kunci pada daftar aspirasi admin.
  2. Siswa dapat mengedit/menghapus aspirasi miliknya selama status masih `"Menunggu"`.
  3. Halaman profil dan ubah kata sandi.
  4. Opsi *"kirim sebagai anonim"* (identitas disembunyikan di tampilan, tetap tersimpan di database).
* **Tingkat Sedang (3 Poin/fitur):**
  5. Grafik statistik per kategori atau per bulan (mis. menggunakan `Chart.js`).
  6. Ekspor laporan ke PDF / Excel / CSV.
  7. Tingkat prioritas (`Rendah` / `Sedang` / `Tinggi`) beserta pengurutannya.
  8. Notifikasi email ke siswa saat status berubah (menggunakan Mailpit pada Laragon).
  9. Papan *"Aspirasi Selesai"* publik sebagai bentuk transparansi.
* **Tingkat Sulit (5 Poin/fitur):**
  10. CRUD Kategori oleh admin.
  11. Peran ketiga (mis. Kepala Sekolah hanya-lihat, atau Petugas Sarpras).
  12. Riwayat perubahan status (tabel log + tampilan timeline).
  13. Fitur dukungan/upvote (satu siswa satu suara per aspirasi).
  14. Nomor tiket unik dan halaman pelacakan aspirasi.

---

## 5. Desain Database & Arsitektur Rute

### 5.1. Rancangan Tabel & Relasi (ERD)
1. **`users`**: `id` (PK), `name`, `email` (Unique), `password`, `kelas`, `role` (default: `'siswa'`), `timestamps`.
2. **`kategoris`**: `id` (PK), `nama`, `timestamps`.
3. **`aspirasis`**: `id` (PK), `user_id` (FK $\rightarrow$ `users.id`), `kategori_id` (FK $\rightarrow$ `kategoris.id`), `judul`, `lokasi`, `pesan`, `foto` (nullable), `status` (enum/string: `'menunggu'`, `'diproses'`, `'selesai'`, default: `'menunggu'`), `tanggapan` (nullable), `timestamps`.

**Relasi Model Eloquent:**
* `User` `hasMany` `Aspirasi` $\leftrightarrow$ `Aspirasi` `belongsTo` `User`
* `Kategori` `hasMany` `Aspirasi` $\leftrightarrow$ `Aspirasi` `belongsTo` `Kategori`

### 5.2. Spesifikasi Routing

| Hak Akses / Middleware | HTTP Method | URL Endpoint | Deskripsi Fungsi |
| :--- | :--- | :--- | :--- |
| Publik (`guest`/all) | `GET` | `/` | Beranda publik (ringkasan statistik saja) |
| Tamu (`guest`) | `GET` / `POST` | `/login`, `/register` | Halaman & proses autentikasi |
| Siswa (`auth`) | `GET` | `/aspirasi/create` | Form kirim aspirasi baru |
| Siswa (`auth`) | `POST` | `/aspirasi` | Proses simpan aspirasi & upload foto |
| Siswa (`auth`) | `GET` | `/aspirasi-saya` | Daftar aspirasi milik pengguna yang login |
| Siswa (`auth`) | `GET` | `/aspirasi/{id}` | Detail aspirasi (dengan validasi pemilik) |
| Admin (`auth`, `admin`) | `GET` | `/admin/dashboard` | Halaman statistik utama admin |
| Admin (`auth`, `admin`) | `GET` | `/admin/aspirasi` | Daftar semua aspirasi + filter |
| Admin (`auth`, `admin`) | `GET` | `/admin/aspirasi/{id}` | Halaman detail & form tanggapan admin |
| Admin (`auth`, `admin`) | `PUT` / `PATCH` | `/admin/aspirasi/{id}` | Update status & tanggapan admin |
| Admin (`auth`, `admin`) | `DELETE` | `/admin/aspirasi/{id}` | Hapus data aspirasi |

---

## 6. Spesifikasi Desain UI/UX (Design Requirements)
* **Konsistensi Layout:** Wajib menggunakan Blade Master Layout (`layouts/app.blade.php` dengan `@extends` / `@yield` atau Blade Component) sehingga Navbar tidak ditulis berulang di setiap view.
* **Komponen & Grid:** Menggunakan sistem Grid dan Cards (Bootstrap 5 / Tailwind CSS) secara rapi dan proporsional.
* **Standar Warna Semantik:**
  * **Badge Status:** `Menunggu` = Kuning (`warning`), `Diproses` = Biru (`primary`/`info`), `Selesai` = Hijau (`success`).
  * **Tombol Aksi:** Simpan/Kirim menggunakan `Primary`/`Success`; Hapus menggunakan `Danger` (merah).
* **Empty State & Notifikasi:** Menampilkan pesan ramah ketika daftar kosong (contoh: *"Belum ada aspirasi"*) serta menampilkan komponen *flash message* setelah setiap transaksi data.
* **Responsivitas Mobile:** Tampilan wajib adaptif dan tetap rapi tanpa terpotong (*horizontal overflow*) saat dibuka pada ukuran layar smartphone.

---

## 7. Deliverables & Kriteria Penyelesaian (Acceptance Criteria)
1. **Dokumen Perancangan (PDF / Gambar):**
   * **Flowchart 1 (Akses Admin):** Alur akses `/admin/dashboard` dengan 2 simbol *Decision* (cek login & cek role admin).
   * **Flowchart 2 (Kirim Aspirasi):** Alur pengisian form hingga simpan ke database dengan *Decision* validasi input.
   * **ERD:** Diagram relasi tabel `users`, `kategoris`, dan `aspirasis` beserta Primary Key & Foreign Key.
2. **Video Demonstrasi (Screen Recording Tanpa Jeda/Edit):**
   * **Skenario Tamu:** Cek beranda publik, uji tampilan mobile/responsive, uji akses paksa ke `/admin/dashboard` & `/aspirasi/create` (redirect ke `/login`).
   * **Skenario Siswa:** Register akun baru, uji validasi form kosong, kirim aspirasi + upload foto, cek detail aspirasi, uji akses paksa ke `/admin/dashboard` & ID aspirasi akun lain (ditolak), lalu Logout.
   * **Skenario Admin:** Login via akun Seeder, cek peningkatan angka statistik dashboard, uji filter status/kategori, ubah status ke `Diproses` + isi tanggapan, hapus data uji coba dengan konfirmasi, lalu Logout.
   * **Verifikasi Silang & Fitur Mandiri:** Login kembali sebagai siswa untuk memverifikasi perubahan status & tanggapan, lalu demonstrasikan minimal 2 fitur pengembangan mandiri.
3. **Paket File Pengumpulan:**
   * File `.zip` source code proyek Laravel (tanpa folder `vendor` dan `node_modules`).
   * File ekspor database MySQL (`.sql`).
   * File `.txt` berisi daftar akun uji (email & password siswa/admin) serta daftar fitur mandiri yang dikerjakan.