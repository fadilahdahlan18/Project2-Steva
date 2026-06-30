<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Hash;

class MuridJadwalFilterTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test murid hanya melihat jadwal kelas yang diikutinya.
     */
    public function test_murid_hanya_melihat_jadwal_kelas_yang_diikuti()
    {
        // Clear existing jadwal records within transaction to prevent conflicts with seeders
        Jadwal::query()->delete();

        // 1. Buat beberapa jadwal latihan dengan variasi kategori & jenis kelas
        $jadwalCocok = Jadwal::create([
            'nama_kelas' => 'Kelas Tari Rampak Pemula',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'hari' => 'senin',
            'jam' => '16:00',
        ]);

        $jadwalBedaKategori = Jadwal::create([
            'nama_kelas' => 'Kelas Tari Rampak Madya',
            'kategori_kelas' => 'madya',
            'jenis_kelas' => 'rampak',
            'hari' => 'selasa',
            'jam' => '16:00',
        ]);

        $jadwalBedaJenis = Jadwal::create([
            'nama_kelas' => 'Kelas Tari Reguler Pemula',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'reguler',
            'hari' => 'rabu',
            'jam' => '16:00',
        ]);

        // 2. Buat user murid yang terdaftar di kelas: Pemula - Rampak
        $murid = User::create([
            'nama' => 'Murid Pemula Rampak',
            'username' => 'muridrampak',
            'email' => 'muridrampak@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        // 3. Login sebagai murid tersebut
        $this->actingAs($murid);

        // --- UJI DASHBOARD MURID ---
        $responseDashboard = $this->get(route('murid.dashboard'));
        $responseDashboard->assertStatus(200);

        // Harus menampilkan jadwal yang cocok
        $responseDashboard->assertSee($jadwalCocok->nama_kelas);
        // Tidak boleh menampilkan jadwal di luar kelasnya
        $responseDashboard->assertDontSee($jadwalBedaKategori->nama_kelas);
        $responseDashboard->assertDontSee($jadwalBedaJenis->nama_kelas);

        // --- UJI HALAMAN JADWAL MURID ---
        $responseJadwal = $this->get(route('murid.jadwal'));
        $responseJadwal->assertStatus(200);

        // Harus menampilkan jadwal yang cocok
        $responseJadwal->assertSee($jadwalCocok->nama_kelas);
        // Tidak boleh menampilkan jadwal di luar kelasnya
        $responseJadwal->assertDontSee($jadwalBedaKategori->nama_kelas);
        $responseJadwal->assertDontSee($jadwalBedaJenis->nama_kelas);
    }

    /**
     * Test that if a coach has both Rampak and Reguler classes,
     * a student who only takes Rampak does NOT see the reguler class schedule.
     */
    public function test_murid_does_not_see_expanded_class_schedule_of_coach()
    {
        Jadwal::query()->delete();

        // 1. Create a coach who teaches both Rampak and Reguler classes (represented by their jenis_kelas)
        $coach = User::create([
            'nama' => 'Pelatih Serba Bisa',
            'username' => 'pelatihserbabisa',
            'email' => 'coachboth@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak,reguler',
        ]);

        // 2. Create only a Rampak schedule in the database taught by this coach
        $jadwalRampak = Jadwal::create([
            'pelatih_id' => $coach->id,
            'nama_kelas' => 'Kelas Tari Rampak Pemula',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'hari' => 'jumat',
            'jam' => '14.00-17.00',
        ]);

        // 3. Create a student who is ONLY enrolled in Rampak
        $student = User::create([
            'nama' => 'Murid Rampak Saja',
            'username' => 'muridrampakonly',
            'email' => 'rampaksaja@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        // 4. Log in as student
        $this->actingAs($student);

        // 5. Access the schedules page
        $responseJadwal = $this->get(route('murid.jadwal'));
        $responseJadwal->assertStatus(200);

        // Student should see the Rampak class schedule
        $responseJadwal->assertSee('Kelas Tari Rampak Pemula');

        // Student should NOT see the dynamically expanded Reguler class schedule
        $responseJadwal->assertDontSee('Kelas Pemula Reguler');
    }
}
