<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pelitanusantara.sch.id'],
            [
                'name' => 'Rina Kusumawati',
                'password' => Hash::make('admin12345'),
                'kelas' => null,
                'role' => 'admin',
            ],
        );

        User::updateOrCreate(
            ['email' => 'siswa@pelitanusantara.sch.id'],
            [
                'name' => 'Bagas Prakoso',
                'password' => Hash::make('siswa12345'),
                'kelas' => 'XI RPL 1',
                'role' => 'siswa',
            ],
        );
    }
}
