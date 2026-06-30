<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Hash;

class ClassDataIsolationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test murid only sees absensi matching their class.
     */
    public function test_murid_only_sees_absensi_matching_their_class()
    {
        // 1. Create student in Pemula - Rampak
        $student = User::create([
            'nama' => 'Murid Pemula Rampak',
            'username' => 'muridrampakiso',
            'email' => 'muridrampakiso@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        // 2. Create matched and unmatched schedules
        $jadwalCocok = Jadwal::create([
            'nama_kelas' => 'Kelas Tari Rampak Pemula',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'hari' => 'jumat',
            'jam' => '14:00',
        ]);

        $jadwalBeda = Jadwal::create([
            'nama_kelas' => 'Kelas Tari Reguler Madya',
            'kategori_kelas' => 'madya',
            'jenis_kelas' => 'reguler',
            'hari' => 'sabtu',
            'jam' => '14:00',
        ]);

        // 3. Create absensi records
        $absensiCocok = Absensi::create([
            'user_id' => $student->id,
            'jadwal_id' => $jadwalCocok->id,
            'tanggal' => '2026-06-04',
            'status' => 'hadir',
        ]);

        $absensiBeda = Absensi::create([
            'user_id' => $student->id,
            'jadwal_id' => $jadwalBeda->id,
            'tanggal' => '2026-06-05',
            'status' => 'hadir',
        ]);

        $this->actingAs($student);

        // Check dashboard
        $responseDashboard = $this->get(route('murid.dashboard'));
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertSee($jadwalCocok->nama_kelas);
        $responseDashboard->assertDontSee($jadwalBeda->nama_kelas);

        // Check absensi index
        $responseAbsensi = $this->get(route('murid.absensi'));
        $responseAbsensi->assertStatus(200);
        $responseAbsensi->assertSee($jadwalCocok->nama_kelas);
        $responseAbsensi->assertDontSee($jadwalBeda->nama_kelas);
    }

    /**
     * Test murid only sees pembayaran matching their class or general payments.
     */
    public function test_murid_only_sees_pembayaran_matching_their_class_or_general()
    {
        $student = User::create([
            'nama' => 'Murid Pemula Rampak',
            'username' => 'muridrampakiso',
            'email' => 'muridrampakiso@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        $jadwalCocok = Jadwal::create([
            'nama_kelas' => 'Kelas Tari Rampak Pemula',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'hari' => 'jumat',
            'jam' => '14:00',
        ]);

        $jadwalBeda = Jadwal::create([
            'nama_kelas' => 'Kelas Tari Reguler Madya',
            'kategori_kelas' => 'madya',
            'jenis_kelas' => 'reguler',
            'hari' => 'sabtu',
            'jam' => '14:00',
        ]);

        $pembayaranCocok = Pembayaran::create([
            'user_id' => $student->id,
            'jadwal_id' => $jadwalCocok->id,
            'tanggal' => '2026-06-04',
            'jumlah' => 20000,
            'metode' => 'transfer',
            'status' => 'disetujui',
            'keterangan' => 'Bayar cocok',
        ]);

        $pembayaranGeneral = Pembayaran::create([
            'user_id' => $student->id,
            'jadwal_id' => null, // General
            'tanggal' => '2026-06-05',
            'jumlah' => 20000,
            'metode' => 'transfer',
            'status' => 'disetujui',
            'keterangan' => 'Bayar general',
        ]);

        $pembayaranBeda = Pembayaran::create([
            'user_id' => $student->id,
            'jadwal_id' => $jadwalBeda->id, // Unmatched class
            'tanggal' => '2026-06-06',
            'jumlah' => 20000,
            'metode' => 'transfer',
            'status' => 'disetujui',
            'keterangan' => 'Bayar beda',
        ]);

        $this->actingAs($student);

        $response = $this->get(route('murid.pembayaran'));
        $response->assertStatus(200);
        $response->assertSee('04 Jun 2026');
        $response->assertSee('05 Jun 2026');
        $response->assertDontSee('06 Jun 2026');
    }

    /**
     * Test pelatih only sees students from their classes in the absensi create murid list.
     */
    public function test_pelatih_only_sees_students_from_their_classes_on_absensi_create()
    {
        // 1. Create coach for Pemula - Rampak
        $coach = User::create([
            'nama' => 'Pelatih Rampak',
            'username' => 'pelatihrampakiso',
            'email' => 'pelatihunik@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih',
            'status' => 'aktif',
        ]);

        // 2. Create schedule taught by coach
        $jadwal = Jadwal::create([
            'nama_kelas' => 'Kelas Tari Rampak Pemula',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'hari' => 'jumat',
            'jam' => '14:00',
            'pelatih_id' => $coach->id,
        ]);

        // 3. Create matched student and unmatched student
        $studentCocok = User::create([
            'nama' => 'Murid Cocok',
            'username' => 'muridcocokiso',
            'email' => 'cocokiso@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        $studentBeda = User::create([
            'nama' => 'Murid Beda',
            'username' => 'muridbedaiso',
            'email' => 'bedaiso@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'madya',
            'jenis_kelas' => 'reguler',
        ]);

        $this->actingAs($coach);

        // Check create form
        $responseCreate = $this->get(route('pelatih.absensi.create'));
        $responseCreate->assertStatus(200);

        // Check eligible students AJAX endpoint
        $responseEligible = $this->getJson(route('pelatih.absensi.eligible-students', [
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2026-06-04'
        ]));
        $responseEligible->assertStatus(200);
        $responseEligible->assertJsonFragment(['nama' => 'Murid Cocok']);
        $responseEligible->assertJsonMissing(['nama' => 'Murid Beda']);
    }
}
