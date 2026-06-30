<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Pembayaran;
use App\Models\Absensi;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminLaporanFilterTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;
    protected $coach1;
    protected $coach2;
    protected $scheduleA;
    protected $scheduleB;
    protected $studentA;
    protected $studentB;
    protected $paymentA;
    protected $paymentB;
    protected $absensiA;
    protected $absensiB;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Admin
        $this->admin = User::create([
            'nama' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admintest@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        // 2. Create Coaches
        $this->coach1 = User::create([
            'nama' => 'Coach One',
            'username' => 'coachone',
            'email' => 'coachone@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih',
            'status' => 'aktif',
        ]);

        $this->coach2 = User::create([
            'nama' => 'Coach Two',
            'username' => 'coachtwo',
            'email' => 'coachtwo@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih',
            'status' => 'aktif',
        ]);

        // 3. Create Schedules/Classes
        $this->scheduleA = Jadwal::create([
            'nama_kelas' => 'Kelas Rampak Pemula',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'hari' => 'Jumat',
            'jam' => '14:00-17:00',
            'pelatih_id' => $this->coach1->id,
        ]);

        $this->scheduleB = Jadwal::create([
            'nama_kelas' => 'Kelas Reguler Madya',
            'kategori_kelas' => 'madya',
            'jenis_kelas' => 'reguler',
            'hari' => 'Sabtu',
            'jam' => '14:00-15:00',
            'pelatih_id' => $this->coach2->id,
        ]);

        // 4. Create Students (Murid)
        $this->studentA = User::create([
            'nama' => 'Student A',
            'username' => 'studenta',
            'email' => 'studenta@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        $this->studentB = User::create([
            'nama' => 'Student B',
            'username' => 'studentb',
            'email' => 'studentb@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'madya',
            'jenis_kelas' => 'reguler',
        ]);

        // 5. Create Payments (Pembayaran)
        $this->paymentA = Pembayaran::create([
            'user_id' => $this->studentA->id,
            'jadwal_id' => $this->scheduleA->id,
            'jumlah' => 150000,
            'status' => 'disetujui',
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'metode' => 'transfer',
        ]);

        $this->paymentB = Pembayaran::create([
            'user_id' => $this->studentB->id,
            'jadwal_id' => $this->scheduleB->id,
            'jumlah' => 200000,
            'status' => 'disetujui',
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'metode' => 'transfer',
        ]);

        // 6. Create Absensi
        $this->absensiA = Absensi::create([
            'user_id' => $this->studentA->id,
            'jadwal_id' => $this->scheduleA->id,
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'status' => 'hadir',
        ]);

        $this->absensiB = Absensi::create([
            'user_id' => $this->studentB->id,
            'jadwal_id' => $this->scheduleB->id,
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'status' => 'hadir',
        ]);
    }

    /**
     * Test admin can access Laporan Ringkasan and view all data when no filter is selected.
     */
    public function test_admin_sees_all_summary_data_by_default()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.laporan'));
        $response->assertStatus(200);

        $data = $response->original->getData();
        
        // Assert overall statistics match total counts in Transactional Database
        $this->assertTrue($data['totalMurid'] >= 2);
        $this->assertTrue($data['totalPelatih'] >= 2);
        $this->assertTrue($data['totalPendapatan'] >= 350000);
    }

    /**
     * Test admin filtering Laporan Ringkasan by specific class.
     */
    public function test_admin_can_filter_summary_data_by_class()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.laporan', [
            'jadwal_id' => $this->scheduleA->id,
        ]));
        $response->assertStatus(200);

        $data = $response->original->getData();

        // Under Schedule A (pemula rampak):
        // Only students matching (kategori_kelas = pemula, jenis_kelas = rampak)
        $expectedMuridCount = User::where('role', 'murid')
            ->where('kategori_kelas', $this->scheduleA->kategori_kelas)
            ->where('jenis_kelas', 'like', '%' . $this->scheduleA->jenis_kelas . '%')
            ->count();
        $this->assertEquals($expectedMuridCount, $data['totalMurid']);
        
        // Only coach1 teaches Schedule A
        $this->assertEquals(1, $data['totalPelatih']);
        
        // Only payments associated with Schedule A
        $expectedPaymentSum = Pembayaran::where('status', 'disetujui')
            ->where('jadwal_id', $this->scheduleA->id)
            ->sum('jumlah');
        $this->assertEquals($expectedPaymentSum, $data['totalPendapatan']);
    }

    /**
     * Test admin can access Laporan Monitoring and see all details by default.
     */
    public function test_admin_sees_all_monitoring_details_by_default()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.laporan.monitoring', [
            'start_date' => Carbon::now()->startOfMonth()->format('Y-m-d'),
            'end_date' => Carbon::now()->endOfMonth()->format('Y-m-d'),
        ]));
        $response->assertStatus(200);

        $data = $response->original->getData();

        // Verify that both students are in detailMurid
        $muridIds = $data['detailMurid']->pluck('id')->toArray();
        $this->assertContains($this->studentA->id, $muridIds);
        $this->assertContains($this->studentB->id, $muridIds);

        // Verify that both payments are in detailPembayaran
        $paymentIds = $data['detailPembayaran']->pluck('id')->toArray();
        $this->assertContains($this->paymentA->id, $paymentIds);
        $this->assertContains($this->paymentB->id, $paymentIds);

        // Verify that both attendance records are in detailAbsensi
        $absensiIds = $data['detailAbsensi']->pluck('id')->toArray();
        $this->assertContains($this->absensiA->id, $absensiIds);
        $this->assertContains($this->absensiB->id, $absensiIds);
    }

    /**
     * Test admin filtering Laporan Monitoring by specific class.
     */
    public function test_admin_can_filter_monitoring_details_by_class()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.laporan.monitoring', [
            'start_date' => Carbon::now()->startOfMonth()->format('Y-m-d'),
            'end_date' => Carbon::now()->endOfMonth()->format('Y-m-d'),
            'jadwal_id' => $this->scheduleA->id,
        ]));
        $response->assertStatus(200);

        $data = $response->original->getData();

        // Under Schedule A (pemula rampak):
        // Only studentA should be present in detailed student list
        $muridIds = $data['detailMurid']->pluck('id')->toArray();
        $this->assertContains($this->studentA->id, $muridIds);
        $this->assertNotContains($this->studentB->id, $muridIds);

        // Only paymentA should be present in detailed payments
        $paymentIds = $data['detailPembayaran']->pluck('id')->toArray();
        $this->assertContains($this->paymentA->id, $paymentIds);
        $this->assertNotContains($this->paymentB->id, $paymentIds);

        // Only absensiA should be present in detailed attendance records
        $absensiIds = $data['detailAbsensi']->pluck('id')->toArray();
        $this->assertContains($this->absensiA->id, $absensiIds);
        $this->assertNotContains($this->absensiB->id, $absensiIds);
    }
}
