<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentApprovalTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test murid registration results in pending status.
     */
    public function test_student_registration_sets_pending_status()
    {
        $response = $this->post(route('register.murid.post'), [
            'nama' => 'Test Murid Baru',
            'username' => 'testmuridbaru',
            'email' => 'testmurid@steva.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'no_hp' => '081234567891',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => ['rampak'],
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Registrasi sebagai Murid berhasil! Menunggu persetujuan Admin.');

        $this->assertDatabaseHas('users', [
            'email' => 'testmurid@steva.com',
            'role' => 'murid',
            'status' => 'pending',
        ]);
    }

    /**
     * Test pending student cannot log in.
     */
    public function test_pending_student_cannot_login()
    {
        $student = User::create([
            'nama' => 'Pending Student',
            'username' => 'pendingstudent',
            'email' => 'pendingstudent@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'pending',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        $response = $this->post(route('login.post'), [
            'username' => 'pendingstudent',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['username']);
        $this->assertFalse(auth()->check());
    }

    /**
     * Test admin can approve pending student.
     */
    public function test_admin_can_approve_student()
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

        // Create pending student
        $student = User::create([
            'nama' => 'Pending Student',
            'username' => 'pendingstudent',
            'email' => 'pendingstudent@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'pending',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        // Log in as admin
        $this->actingAs($admin);

        // Approve student
        $response = $this->post(route('admin.users.approve', $student->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Akun ' . $student->nama . ' berhasil disetujui.');

        $student->refresh();
        $this->assertEquals('aktif', $student->status);
    }

    /**
     * Test admin can reject pending student.
     */
    public function test_admin_can_reject_student()
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

        // Create pending student
        $student = User::create([
            'nama' => 'Pending Student',
            'username' => 'pendingstudent',
            'email' => 'pendingstudent@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'pending',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        // Log in as admin
        $this->actingAs($admin);

        // Reject student
        $response = $this->post(route('admin.users.reject', $student->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Akun ' . $student->nama . ' telah ditolak.');

        $student->refresh();
        $this->assertEquals('ditolak', $student->status);
    }
}
