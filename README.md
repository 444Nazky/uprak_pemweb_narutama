# Sistem Kotak Aspirasi Sekolah

Aplikasi web untuk menampung laporan sarana prasarana dan masukan siswa SMK Plus Pelita Nusantara Bogor. Dibuat untuk Ujian Praktik Kejuruan Pemrograman Web kelas XI RPL.

## Teknologi

- Laravel 13 (PHP 8.3 atau lebih baru), pola MVC
- Blade Templating dengan master layout dan komponen
- Bootstrap 5.3 untuk grid, form, modal, dan pagination
- CSS kustom di `public/css/app.css` sebagai design system
- Chart.js untuk grafik dashboard admin
- MySQL atau MariaDB
- Seluruh aset pihak ketiga disimpan lokal di `public/vendor` dan `public/fonts`, jadi aplikasi tetap tampil normal tanpa internet

## Fitur

Fitur wajib:

- Registrasi siswa dengan kolom kelas, login, logout melalui POST dan CSRF
- Middleware `auth` dan `AdminMiddleware` dengan alias `admin`
- Validasi kepemilikan data pada detail aspirasi, siswa lain mendapat 403
- CRUD aspirasi siswa dengan unggah foto bukti maksimal 2 MB
- Panel admin: dashboard statistik, filter status dan kategori, pagination, tanggapan, hapus dengan konfirmasi
- Badge status semantik, flash message, empty state, dan layout responsif

Fitur pengembangan mandiri, total 10 poin:

1. Pencarian kata kunci pada daftar aspirasi admin, mencakup judul, isi, lokasi, dan nama siswa (2 poin)
2. Siswa dapat mengubah atau menghapus aspirasinya selama status masih Menunggu (2 poin)
3. Tingkat prioritas Rendah, Sedang, Tinggi beserta pengurutan di panel admin (3 poin)
4. Grafik statistik per kategori dan enam bulan terakhir dengan Chart.js (3 poin)

## Akun uji

| Peran | Email | Kata sandi |
| --- | --- | --- |
| Admin | admin@pelitanusantara.sch.id | admin12345 |
| Siswa | siswa@pelitanusantara.sch.id | siswa12345 |

Seeder juga membuat akun siswa lain dengan kata sandi `siswa12345`: `nadya.prameswari@`, `rafi.hidayat@`, `salsabila.rahma@`, dan `yoga.prasetya@pelitanusantara.sch.id`. Daftar lengkap ada di `deliverables/akun-dan-fitur.txt`.

## Cara menjalankan

1. Pastikan PHP 8.3+, Composer, dan MySQL atau MariaDB tersedia.
2. Salin konfigurasi lingkungan.

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Buat database `aspirasi_sekolah`, lalu sesuaikan `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`.
4. Pasang dependensi dan siapkan data.

   ```bash
   composer install
   php artisan migrate --seed
   php artisan storage:link
   ```

5. Jalankan server.

   ```bash
   php artisan serve
   ```

6. Buka `http://127.0.0.1:8000`.

Tidak perlu menjalankan `npm install` atau `npm run build`. Aset CSS, JavaScript, ikon, dan font sudah tersedia di folder `public`.

Untuk memulihkan data demo, jalankan `php artisan migrate:fresh --seed`. Untuk memakai file SQL yang disertakan, impor `deliverables/aspirasi_sekolah.sql` melalui phpMyAdmin atau perintah `mysql -u root -p < deliverables/aspirasi_sekolah.sql`.

## Struktur penting

| Lokasi | Isi |
| --- | --- |
| `app/Http/Controllers` | Controller publik, siswa, autentikasi, dan admin |
| `app/Http/Middleware/AdminMiddleware.php` | Pemeriksaan peran admin |
| `app/Models` | Model `User`, `Kategori`, `Aspirasi` beserta relasi |
| `database/migrations` | Skema `users`, `kategoris`, `aspirasis` |
| `database/seeders` | AdminSeeder, KategoriSeeder, AspirasiSeeder |
| `resources/views` | Master layout, komponen, halaman publik, siswa, admin |
| `public/css/app.css` | Design system dan seluruh komponen visual |
| `deliverables` | Flowchart, ERD, ekspor SQL, daftar akun |

## Rute utama

| Method | URL | Akses |
| --- | --- | --- |
| GET | `/` | Publik |
| GET/POST | `/login`, `/register` | Tamu |
| POST | `/logout` | Login |
| GET | `/aspirasi/create` | Siswa |
| POST | `/aspirasi` | Siswa |
| GET | `/aspirasi-saya` | Siswa |
| GET | `/aspirasi/{id}` | Pemilik laporan |
| GET/PUT/DELETE | `/aspirasi/{id}/edit`, `/aspirasi/{id}` | Pemilik, status Menunggu |
| GET | `/admin/dashboard` | Admin |
| GET | `/admin/aspirasi` | Admin |
| GET | `/admin/aspirasi/{id}` | Admin |
| PUT/PATCH | `/admin/aspirasi/{id}` | Admin |
| DELETE | `/admin/aspirasi/{id}` | Admin |

## Catatan keamanan

- Semua form memakai token CSRF, termasuk tombol logout
- Password disimpan dengan hash bawaan Laravel
- Login dibatasi lima percobaan per menit per kombinasi email dan IP
- Foto divalidasi sebagai gambar JPG atau PNG dengan batas 2 MB dan disimpan di `storage/app/public/bukti`
- Foto ikut terhapus saat laporan dihapus
- Siswa tidak dapat membuka, mengubah, atau menghapus laporan milik siswa lain
- Perubahan laporan dikunci setelah status berubah dari Menunggu
