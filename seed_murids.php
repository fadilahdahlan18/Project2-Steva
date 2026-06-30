<?php
$jadwals = App\Models\Jadwal::all();
foreach ($jadwals as $j) {
    for ($i = 1; $i <= 2; $i++) {
        $email = strtolower($j->kategori_kelas . '_' . $j->jenis_kelas . '_' . $i) . '@gmail.com';
        if (!App\Models\User::where('email', $email)->exists()) {
            App\Models\User::create([
                'nama' => 'Murid ' . ucfirst($j->kategori_kelas) . ' ' . ucfirst($j->jenis_kelas) . ' ' . $i,
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'murid',
                'status' => 'aktif',
                'kategori_kelas' => $j->kategori_kelas,
                'jenis_kelas' => $j->jenis_kelas,
            ]);
        }
    }
}
echo "Seeded murids!\n";
