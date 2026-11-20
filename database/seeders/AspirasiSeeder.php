<?php

namespace Database\Seeders;

use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AspirasiSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = Kategori::pluck('id', 'nama');

        $siswa = collect([
            ['name' => 'Bagas Prakoso', 'kelas' => 'XI RPL 1'],
            ['name' => 'Nadya Prameswari', 'kelas' => 'XI RPL 2'],
            ['name' => 'Rafi Hidayat', 'kelas' => 'X TKJ 1'],
            ['name' => 'Salsabila Rahma', 'kelas' => 'XII AKL 1'],
            ['name' => 'Yoga Prasetya', 'kelas' => 'XI RPL 1'],
        ])->map(function (array $siswa) {
            return User::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '.', $siswa['name'])).'@pelitanusantara.sch.id'],
                [
                    'name' => $siswa['name'],
                    'kelas' => $siswa['kelas'],
                    'role' => 'siswa',
                    'password' => Hash::make('siswa12345'),
                ],
            );
        ]);

        $data = [
            ['AC ruang 11 rusak', 'Ruang 11, Gedung A', 'AC di ruang 11 sudah tiga minggu tidak dingin. Saat jam praktik siang ruangan terasa panas dan siswa sulit fokus.', 'Sarana & Prasarana', 'selesai', 'tinggi', 96, 'Teknisi sudah memperbaiki unit AC pada 12 November. Mohon laporkan kembali jika suhu belum normal.', 'Nadya Prameswari'],
            ['Keran toilet lantai 2 bocor', 'Toilet lantai 2, Gedung B', 'Keran air di wastafel toilet lantai 2 terus menetes meskipun sudah ditutup. Lantai jadi licin.', 'Kebersihan', 'selesai', 'sedang', 74, 'Keran sudah diganti oleh petugas kebersihan. Lantai juga sudah dibersihkan.', 'Salsabila Rahma'],
            ['Proyektor lab komputer mati', 'Lab Komputer 2', 'Proyektor di Lab Komputer 2 tidak menyala saat praktik. Guru terpaksa menjelaskan tanpa tampilan layar.', 'Sarana & Prasarana', 'diproses', 'tinggi', 21, 'Proyektor sedang diperiksa di bagian sarana. Perkiraan selesai minggu ini.', 'Rafi Hidayat'],
            ['Tempat sampah kantin kurang', 'Kantin sekolah', 'Tempat sampah di area kantin hanya satu, sehingga banyak sampah plastik berserakan di meja makan.', 'Kebersihan', 'diproses', 'sedang', 14, null, 'Yoga Prasetya'],
            ['Usul jam tambahan praktik RPL', 'Lab RPL 1', 'Kami berharap ada tambahan satu sesi praktik pemrograman setiap Jumat untuk menyelesaikan proyek akhir.', 'Kegiatan Belajar', 'menunggu', 'sedang', 6, null, 'Bagas Prakoso'],
            ['Kursi kelas XII AKL goyang', 'Ruang XII AKL 1', 'Ada empat kursi yang kakinya longgar dan berisiko patah saat dipakai. Mohon diganti atau diperbaiki.', 'Sarana & Prasarana', 'menunggu', 'tinggi', 3, null, 'Salsabila Rahma'],
            ['Lampu koridor lantai 1 mati', 'Koridor lantai 1', 'Dua lampu di koridor dekat ruang guru mati, koridor jadi gelap saat pulang sekolah.', 'Sarana & Prasarana', 'menunggu', 'sedang', 2, null, 'Nadya Prameswari'],
            ['Buku paket pemrograman kurang', 'Perpustakaan', 'Stok buku pemrograman web hanya lima eksemplar untuk dua kelas. Siswa kesulitan belajar mandiri.', 'Kegiatan Belajar', 'diproses', 'rendah', 11, null, 'Bagas Prakoso'],
            ['Air dispenser kelas tidak mengalir', 'Ruang XI RPL 1', 'Dispenser di kelas tidak mengeluarkan air sejak Senin. Siswa harus membeli air di kantin.', 'Lain-lain', 'menunggu', 'rendah', 1, null, 'Yoga Prasetya'],
            ['Papan tulis kelas X TKJ retak', 'Ruang X TKJ 1', 'Permukaan papan tulis retak sehingga tulisan guru sulit dibaca dari belakang.', 'Sarana & Prasarana', 'selesai', 'sedang', 51, 'Papan tulis sudah diganti dengan unit baru pada 3 November.', 'Rafi Hidayat'],
        ];

        foreach ($data as [$judul, $lokasi, $pesan, $kategori, $status, $prioritas, $hariLalu, $tanggapan, $nama]) {
            $siswaItem = $siswa->firstWhere('name', $nama);
            $dibuat = now()->subDays($hariLalu);

            Aspirasi::create([
                'user_id' => $siswaItem->id,
                'kategori_id' => $kategoris[$kategori],
                'judul' => $judul,
                'lokasi' => $lokasi,
                'pesan' => $pesan,
                'status' => $status,
                'prioritas' => $prioritas,
                'tanggapan' => $tanggapan,
                'ditanggapi_pada' => $tanggapan ? $dibuat->copy()->addDays(2) : null,
                'created_at' => $dibuat,
                'updated_at' => $tanggapan ? $dibuat->copy()->addDays(2) : $dibuat,
            ]);
        }
    }
}
