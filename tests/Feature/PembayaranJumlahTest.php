<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

class PembayaranJumlahTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test admin store payment fails if amount is empty.
     */
    public function test_admin_store_payment_fails_if_amount_is_empty()
    {
        $admin = User::create([
            'nama' => 'Admin User',
            'username' => 'adminuser',
            'email' => 'adminuser@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $student = User::create([
            'nama' => 'Student Test',
            'username' => 'studenttest',
            'email' => 'student@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
        ]);

        $jadwal = Jadwal::create([
            'nama_kelas' => 'Kelas Test',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'hari' => 'jumat',
            'jam' => '14:00',
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.pembayaran.store'), [
            'user_id' => $student->id,
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2026-06-04',
            'jumlah' => '', // Empty
            'metode' => 'cash',
            'status' => 'disetujui',
        ]);

        $response->assertSessionHasErrors(['jumlah']);
    }

    /**
     * Test admin store payment fails if amount is negative.
     */
    public function test_admin_store_payment_fails_if_amount_is_negative()
    {
        $admin = User::create([
            'nama' => 'Admin User',
            'username' => 'adminuser',
            'email' => 'adminuser@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $student = User::create([
            'nama' => 'Student Test',
            'username' => 'studenttest',
            'email' => 'student@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
        ]);

        $jadwal = Jadwal::create([
            'nama_kelas' => 'Kelas Test',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'hari' => 'jumat',
            'jam' => '14:00',
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.pembayaran.store'), [
            'user_id' => $student->id,
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2026-06-04',
            'jumlah' => -5000, // Negative
            'metode' => 'cash',
            'status' => 'disetujui',
        ]);

        $response->assertSessionHasErrors(['jumlah']);
    }

    /**
     * Test murid store payment fails if amount is empty.
     */
    public function test_student_store_payment_fails_if_amount_is_empty()
    {
        $student = User::create([
            'nama' => 'Student Test',
            'username' => 'studenttest',
            'email' => 'student@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
        ]);

        $this->actingAs($student);

        $response = $this->post(route('murid.pembayaran.store'), [
            'jumlah' => '', // Empty
            'tanggal' => '2026-06-04',
            'metode' => 'transfer',
            'bukti_transfer' => UploadedFile::fake()->create('bukti.png', 100, 'image/png'),
        ]);

        $response->assertSessionHasErrors(['jumlah']);
    }

    /**
     * Test murid store payment fails if amount is negative.
     */
    public function test_student_store_payment_fails_if_amount_is_negative()
    {
        $student = User::create([
            'nama' => 'Student Test',
            'username' => 'studenttest',
            'email' => 'student@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
        ]);

        $this->actingAs($student);

        $response = $this->post(route('murid.pembayaran.store'), [
            'jumlah' => -1000, // Negative
            'tanggal' => '2026-06-04',
            'metode' => 'transfer',
            'bukti_transfer' => UploadedFile::fake()->create('bukti.png', 100, 'image/png'),
        ]);

        $response->assertSessionHasErrors(['jumlah']);
    }
}
