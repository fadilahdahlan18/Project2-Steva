<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Absensi;
use Illuminate\Support\Facades\Hash;

class AbsensiOptimasiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test eligible students endpoint returns correct students who have not been absented yet.
     */
    public function test_coach_sees_only_unabsented_students_on_eligible_endpoint()
    {
        // Clean up seeded data that might conflict
        User::where('role', 'murid')->delete();
        Jadwal::query()->delete();

        // 1. Create coach for Pemula - Rampak
        $coach = User::create([
            'nama' => 'Pelatih Rampak',
            'username' => 'pelatihrampak',
            'email' => 'pelatihrampak@steva.com',
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

        // 3. Create two students in Pemula - Rampak
        $studentA = User::create([
            'nama' => 'Murid A',
            'username' => 'murida',
            'email' => 'murida@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        $studentB = User::create([
            'nama' => 'Murid B',
            'username' => 'muridb',
            'email' => 'muridb@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        $this->actingAs($coach);

        // Fetch eligible students initially. Both A and B should be there.
        $response = $this->getJson(route('pelatih.absensi.eligible-students', [
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2026-06-04'
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['nama' => 'Murid A']);
        $response->assertJsonFragment(['nama' => 'Murid B']);

        // Create absensi for student A
        $absensiA = Absensi::create([
            'user_id' => $studentA->id,
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2026-06-04',
            'status' => 'hadir',
        ]);

        // Fetch eligible students again. Only B should be there.
        $response2 = $this->getJson(route('pelatih.absensi.eligible-students', [
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2026-06-04'
        ]));

        $response2->assertStatus(200);
        $response2->assertJsonCount(1);
        $response2->assertJsonFragment(['nama' => 'Murid B']);
        $response2->assertJsonMissing(['nama' => 'Murid A']);

        // If we delete student A's attendance
        $absensiA->delete();

        // Student A should reappear on the endpoint list
        $response3 = $this->getJson(route('pelatih.absensi.eligible-students', [
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2026-06-04'
        ]));

        $response3->assertStatus(200);
        $response3->assertJsonCount(2);
        $response3->assertJsonFragment(['nama' => 'Murid A']);
    }

    /**
     * Test validation fails when submitting duplicate student ids.
     */
    public function test_attendance_submission_fails_with_duplicate_students()
    {
        $coach = User::create([
            'nama' => 'Pelatih Rampak',
            'username' => 'pelatihrampak',
            'email' => 'pelatihrampak@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih',
            'status' => 'aktif',
        ]);

        $jadwal = Jadwal::create([
            'nama_kelas' => 'Kelas Tari Rampak Pemula',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'hari' => 'jumat',
            'jam' => '14:00',
            'pelatih_id' => $coach->id,
        ]);

        $student = User::create([
            'nama' => 'Murid A',
            'username' => 'murida',
            'email' => 'murida@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        $this->actingAs($coach);

        // Submit form with duplicate user_id
        $response = $this->post(route('pelatih.absensi.store'), [
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2026-06-04',
            'absensi' => [
                [
                    'user_id' => $student->id,
                    'status' => 'hadir'
                ],
                [
                    'user_id' => $student->id,
                    'status' => 'hadir'
                ]
            ]
        ]);

        $response->assertSessionHasErrors('absensi.0.user_id');
    }
}
