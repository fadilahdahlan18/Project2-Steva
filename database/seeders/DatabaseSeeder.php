<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Jadwal;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Admin default
        User::updateOrCreate(
            ['email' => 'admin@steva.com'],
            [
                'nama'     => 'admin',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'no_hp'    => '08123456789',
            ]
        );

        // Demo pelatih 1
        $pelatih1 = User::updateOrCreate(
            ['email' => 'pelatih@steva.com'],
            [
                'nama'     => 'Siti Rahayu',
                'username' => 'sitirahayu',
                'password' => Hash::make('pelatih123'),
                'role'     => 'pelatih',
                'no_hp'    => '08234567890',
                'kode_pelatih' => 'PLT-001',
            ]
        );

        // Demo pelatih 2
        $pelatih2 = User::updateOrCreate(
            ['email' => 'pelatih2@steva.com'],
            [
                'nama'     => 'Navita',
                'username' => 'navita',
                'password' => Hash::make('pelatih123'),
                'role'     => 'pelatih',
                'no_hp'    => '08234567891',
                'kode_pelatih' => 'PLT-002',
            ]
        );

        // Demo pelatih 3
        $pelatih3 = User::updateOrCreate(
            ['email' => 'pelatih3@steva.com'],
            [
                'nama'     => 'Ayu Sekar',
                'username' => 'ayusekar',
                'password' => Hash::make('pelatih123'),
                'role'     => 'pelatih',
                'no_hp'    => '08234567892',
                'kode_pelatih' => 'PLT-003',
            ]
        );

        // Demo murid
        User::updateOrCreate(
            ['email' => 'murid@steva.com'],
            [
                'nama'     => 'Budi Santoso',
                'username' => 'budisantoso',
                'password' => Hash::make('murid123'),
                'role'     => 'murid',
                'no_hp'    => '08345678901',
                'kategori_kelas' => 'pemula',
                'jenis_kelas' => 'rampak',
            ]
        );

        // Demo jadwal sesuai jadwal STEVA
        $jadwalData = [
            // ===== RAMPAK (Jumat) =====
            ['nama_kelas' => 'Rampak Pemula', 'hari' => 'Jumat', 'jam' => '14:00-17:00', 'kategori_kelas' => 'pemula', 'jenis_kelas' => 'rampak', 'pelatih_id' => $pelatih1->id],
            ['nama_kelas' => 'Rampak Madya',  'hari' => 'Jumat', 'jam' => '14:00-17:00', 'kategori_kelas' => 'madya',  'jenis_kelas' => 'rampak', 'pelatih_id' => $pelatih2->id],
            ['nama_kelas' => 'Rampak Ahli',   'hari' => 'Jumat', 'jam' => '14:00-17:00', 'kategori_kelas' => 'ahli',   'jenis_kelas' => 'rampak', 'pelatih_id' => $pelatih3->id],

            // ===== REGULER SABTU =====
            ['nama_kelas' => 'Reguler Pemula (Sabtu)', 'hari' => 'Sabtu', 'jam' => '09:00-12:00', 'kategori_kelas' => 'pemula', 'jenis_kelas' => 'reguler', 'pelatih_id' => $pelatih1->id],
            ['nama_kelas' => 'Reguler Madya (Sabtu)',  'hari' => 'Sabtu', 'jam' => '09:00-12:00', 'kategori_kelas' => 'madya',  'jenis_kelas' => 'reguler', 'pelatih_id' => $pelatih2->id],
            ['nama_kelas' => 'Reguler Ahli (Sabtu)',   'hari' => 'Sabtu', 'jam' => '09:00-12:00', 'kategori_kelas' => 'ahli',   'jenis_kelas' => 'reguler', 'pelatih_id' => $pelatih3->id],

            // ===== REGULER MINGGU =====
            ['nama_kelas' => 'Reguler Pemula (Minggu)', 'hari' => 'Minggu', 'jam' => '09:00-12:00', 'kategori_kelas' => 'pemula', 'jenis_kelas' => 'reguler', 'pelatih_id' => $pelatih1->id],
            ['nama_kelas' => 'Reguler Madya (Minggu)',  'hari' => 'Minggu', 'jam' => '09:00-12:00', 'kategori_kelas' => 'madya',  'jenis_kelas' => 'reguler', 'pelatih_id' => $pelatih2->id],
            ['nama_kelas' => 'Reguler Ahli (Minggu)',   'hari' => 'Minggu', 'jam' => '09:00-12:00', 'kategori_kelas' => 'ahli',   'jenis_kelas' => 'reguler', 'pelatih_id' => $pelatih3->id],
        ];

        foreach ($jadwalData as $j) {
            Jadwal::firstOrCreate(
                ['nama_kelas' => $j['nama_kelas']],
                $j
            );
        }
    }
}
