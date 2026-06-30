<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CoachApprovalTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test coach registration results in pending status.
     */
    public function test_coach_registration_sets_pending_status()
    {
        $response = $this->post(route('register.pelatih.post'), [
            'nama' => 'Test Pelatih Baru',
            'username' => 'testpelatihbaru',
            'email' => 'testpelatih@steva.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'no_hp' => '081234567890',
            'kode_khusus' => 'STEVA_HEBAT',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => ['rampak'],
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Registrasi sebagai Pelatih berhasil! Menunggu persetujuan Admin.');

        $this->assertDatabaseHas('users', [
            'email' => 'testpelatih@steva.com',
            'role' => 'pelatih',
            'status' => 'pending',
        ]);
    }

    /**
     * Test pending coach cannot log in.
     */
    public function test_pending_coach_cannot_login()
    {
        $coach = User::create([
            'nama' => 'Pending Coach',
            'username' => 'pendingcoach',
            'email' => 'pendingcoach@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih',
            'status' => 'pending',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        $response = $this->post(route('login.post'), [
            'username' => 'pendingcoach',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['username']);
        $this->assertFalse(auth()->check());
    }

    /**
     * Test admin can approve pending coach.
     */
    public function test_admin_can_approve_coach()
    {
        // Create admin user
        $admin = User::create([
            'nama' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admintest@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        // Create pending coach
        $coach = User::create([
            'nama' => 'Pending Coach',
            'username' => 'pendingcoach',
            'email' => 'pendingcoach@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih',
            'status' => 'pending',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        // Log in as admin
        $this->actingAs($admin);

        // Approve coach
        $response = $this->post(route('admin.users.approve', $coach->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Akun ' . $coach->nama . ' berhasil disetujui.');

        $coach->refresh();
        $this->assertEquals('aktif', $coach->status);
    }

    /**
     * Test admin can reject pending coach.
     */
    public function test_admin_can_reject_coach()
    {
        // Create admin user
        $admin = User::create([
            'nama' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admintest@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        // Create pending coach
        $coach = User::create([
            'nama' => 'Pending Coach',
            'username' => 'pendingcoach',
            'email' => 'pendingcoach@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih',
            'status' => 'pending',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        // Log in as admin
        $this->actingAs($admin);

        // Reject coach
        $response = $this->post(route('admin.users.reject', $coach->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Akun ' . $coach->nama . ' telah ditolak.');

        $coach->refresh();
        $this->assertEquals('ditolak', $coach->status);
    }
}
