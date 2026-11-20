<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama' => 'Sarana & Prasarana', 'keterangan' => 'Ruang kelas, meja, kursi, listrik, dan peralatan sekolah.'],
            ['nama' => 'Kebersihan', 'keterangan' => 'Toilet, kantin, halaman, tempat sampah, dan saluran air.'],
            ['nama' => 'Kegiatan Belajar', 'keterangan' => 'Jadwal, praktik, bahan ajar, dan kenyamanan belajar.'],
            ['nama' => 'Lain-lain', 'keterangan' => 'Masukan lain yang belum masuk kategori di atas.'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::updateOrCreate(
                ['nama' => $kategori['nama']],
                [
                    'slug' => Str::slug($kategori['nama']),
                    'keterangan' => $kategori['keterangan'],
                ],
            );
        }
    }
}
