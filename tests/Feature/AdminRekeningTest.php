<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Rekening;
use Illuminate\Support\Facades\Hash;

class AdminRekeningTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test guest and student cannot view or edit rekening.
     */
    public function test_guest_and_student_blocked_from_rekening_management()
    {
        // Guest check
        $response = $this->get(route('admin.rekening.edit'));
        $response->assertRedirect(route('login'));

        $responsePut = $this->put(route('admin.rekening.update'), [
            'nama_bank' => 'Mandiri',
            'nomor_rekening' => '123456',
            'nama_pemilik' => 'Test Owner',
        ]);
        $responsePut->assertRedirect(route('login'));

        // Student check
        $student = User::create([
            'nama' => 'Murid A',
            'username' => 'muridatest',
            'email' => 'muridatest@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
        ]);

        $this->actingAs($student);

        $responseStudentGet = $this->get(route('admin.rekening.edit'));
        $responseStudentGet->assertStatus(403); // Forbidden by role middleware

        $responseStudentPut = $this->put(route('admin.rekening.update'), [
            'nama_bank' => 'Mandiri',
            'nomor_rekening' => '123456',
            'nama_pemilik' => 'Test Owner',
        ]);
        $responseStudentPut->assertStatus(403);
    }

    /**
     * Test admin can access form and successfully update bank details.
     */
    public function test_admin_can_update_bank_details()
    {
        $admin = User::create([
            'nama' => 'Admin Steva',
            'username' => 'adminsteva',
            'email' => 'adminsteva@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->actingAs($admin);

        // Access edit form
        $responseGet = $this->get(route('admin.rekening.edit'));
        $responseGet->assertStatus(200);
        $responseGet->assertSee('Form Informasi Rekening');

        // Submit updates
        $responsePut = $this->put(route('admin.rekening.update'), [
            'nama_bank' => 'Bank Mandiri',
            'nomor_rekening' => '987654321',
            'nama_pemilik' => 'Tania Eva',
        ]);

        $responsePut->assertRedirect(route('admin.pembayaran'));
        $responsePut->assertSessionHas('success', 'Informasi rekening berhasil diperbarui.');

        // Verify in database
        $this->assertDatabaseHas('rekening', [
            'nama_bank' => 'Bank Mandiri',
            'nomor_rekening' => '987654321',
            'nama_pemilik' => 'Tania Eva',
        ]);
    }

    /**
     * Test validation checks.
     */
    public function test_update_validation_fails_when_fields_are_empty()
    {
        $admin = User::create([
            'nama' => 'Admin Steva',
            'username' => 'adminsteva',
            'email' => 'adminsteva@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->actingAs($admin);

        // Submit empty values
        $response = $this->put(route('admin.rekening.update'), [
            'nama_bank' => '',
            'nomor_rekening' => '',
            'nama_pemilik' => '',
        ]);

        $response->assertSessionHasErrors(['nama_bank', 'nomor_rekening', 'nama_pemilik']);
    }
}
