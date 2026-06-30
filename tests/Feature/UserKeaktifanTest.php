<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserKeaktifanTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test dynamic status remains active if missed training sessions is less than 14.
     */
    /**
     * Test dynamic status remains active if missed training sessions is less than 3.
     */
    public function test_status_remains_active_when_missed_sessions_less_than_3()
    {
        // Student class: Rampak (class day: Friday)
        // If last active was 2 Fridays ago (e.g. 14 days ago)
        $student = User::create([
            'nama' => 'Murid Rampak Test',
            'username' => 'muridrampaktest',
            'email' => 'muridrampaktest@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'last_active_at' => now()->subWeeks(2),
        ]);

        $this->assertEquals(2, $student->getMissedClassDaysCount());
        $this->assertEquals('aktif', $student->keaktifan_status);
    }

    /**
     * Test dynamic status becomes inactive when missed training sessions is 3 or more.
     */
    public function test_status_becomes_inactive_when_missed_sessions_at_least_3()
    {
        // Student class: Rampak (class day: Friday)
        // If last active was 3 weeks ago (3 Fridays missed)
        $student = User::create([
            'nama' => 'Murid Rampak Test',
            'username' => 'muridrampaktest',
            'email' => 'muridrampaktest@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'last_active_at' => now()->subWeeks(3),
        ]);

        $this->assertTrue($student->getMissedClassDaysCount() >= 3);
        $this->assertEquals('tidak aktif', $student->keaktifan_status);
    }

    /**
     * Test reguler student misses 3 weeks (class days: Saturday and Sunday, 2 per week).
     * 3 weeks is 6 sessions.
     */
    public function test_reguler_student_inactivity()
    {
        // Student class: Reguler (class days: Saturday & Sunday)
        // If last active was 3 weeks ago, they missed 3 * 2 = 6 sessions.
        $student = User::create([
            'nama' => 'Murid Reguler Test',
            'username' => 'muridregulertest',
            'email' => 'muridregulertest@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'reguler',
            'last_active_at' => now()->subWeeks(3),
        ]);

        $this->assertEquals(6, $student->getMissedClassDaysCount());
        $this->assertEquals('tidak aktif', $student->keaktifan_status);
    }

    /**
     * Test that logging in updates the last_active_at timestamp to now, reverting status to active.
     */
    public function test_login_updates_last_active_and_reactivates_status()
    {
        $student = User::create([
            'nama' => 'Inactive Student',
            'username' => 'muridpasif',
            'email' => 'muridpasif@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'last_active_at' => now()->subWeeks(3), // Inactive
        ]);

        $this->assertEquals('tidak aktif', $student->keaktifan_status);

        // Perform login
        $response = $this->post(route('login.post'), [
            'username' => 'muridpasif',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('murid.dashboard'));

        $student->refresh();
        $this->assertEquals('aktif', $student->keaktifan_status);
        $this->assertNotNull($student->last_active_at);
        $this->assertTrue($student->last_active_at->isToday());
    }

    /**
     * Test that user activity on protected routes updates the last_active_at timestamp.
     */
    public function test_activity_updates_last_active_at()
    {
        $student = User::create([
            'nama' => 'Active Student',
            'username' => 'muridaktif',
            'email' => 'muridaktif@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'last_active_at' => now()->subDays(10),
        ]);

        // Login student to establish session
        $this->actingAs($student);

        // Access dashboard
        $response = $this->get(route('murid.dashboard'));
        $response->assertStatus(200);

        $student->refresh();
        $this->assertTrue($student->last_active_at->isToday());
    }

    /**
     * Test admin filtering by active/inactive status.
     */
    public function test_admin_filtering_by_keaktifan_status()
    {
        $admin = User::create([
            'nama' => 'Admin User',
            'username' => 'adminuser',
            'email' => 'adminuser@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $activeStudent = User::create([
            'nama' => 'Active Student',
            'username' => 'muridaktif',
            'email' => 'muridaktif@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'last_active_at' => now()->subDays(2),
        ]);

        $inactiveStudent = User::create([
            'nama' => 'Inactive Student',
            'username' => 'muridpasif',
            'email' => 'muridpasif@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'last_active_at' => now()->subWeeks(15),
        ]);

        $this->actingAs($admin);

        // Filter active
        $responseActive = $this->get(route('admin.users', ['role' => 'murid', 'status' => 'aktif']));
        $responseActive->assertSee('muridaktif');
        $responseActive->assertDontSee('muridpasif');

        // Filter inactive
        $responseInactive = $this->get(route('admin.users', ['role' => 'murid', 'status' => 'tidak aktif']));
        $responseInactive->assertSee('muridpasif');
        $responseInactive->assertDontSee('muridaktif');
    }

    /**
     * Test that coach is exempt from inactivity check.
     */
    public function test_coach_exempt_from_inactivity_check()
    {
        $coach = User::create([
            'nama' => 'Pelatih Test',
            'username' => 'pelatihtest',
            'email' => 'pelatih@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'last_active_at' => now()->subWeeks(15),
        ]);

        $this->assertFalse($coach->isTidakAktifKarenaAbsensi());
        $this->assertEquals('aktif', $coach->keaktifan_status);
        $this->assertFalse($coach->autoDeactivateIfInactive());
    }

    /**
     * Test that reactivated user starts absence count from reactivation date.
     */
    public function test_reactivated_user_stays_active()
    {
        $student = User::create([
            'nama' => 'Student Test',
            'username' => 'studenttest',
            'email' => 'studenttest@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'last_active_at' => now()->subWeeks(15),
            'reactivated_at' => now(),
        ]);

        $this->assertEquals(0, $student->getMissedClassDaysCount());
        $this->assertEquals('aktif', $student->keaktifan_status);
        $this->assertFalse($student->autoDeactivateIfInactive());
    }
}
