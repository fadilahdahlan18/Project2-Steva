<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test forgot password page is accessible.
     */
    public function test_forgot_password_page_is_accessible()
    {
        $response = $this->get(route('forgot.password'));
        $response->assertStatus(200);
        $response->assertSee('Lupa Password');
    }

    /**
     * Test verification with valid credentials.
     */
    public function test_forgot_password_verification_with_valid_credentials()
    {
        $user = User::create([
            'nama' => 'Test User Reset',
            'username' => 'userreset',
            'email' => 'userreset@steva.com',
            'no_hp' => '08999999999',
            'password' => Hash::make('oldpassword'),
            'role' => 'murid',
        ]);

        $response = $this->post(route('forgot.password.post'), [
            'username' => 'userreset',
            'email' => 'userreset@steva.com',
            'no_hp' => '08999999999',
        ]);

        $response->assertRedirect(route('reset.password'));
        $response->assertSessionHas('password_reset_user_id', $user->id);
    }

    /**
     * Test verification with invalid credentials.
     */
    public function test_forgot_password_verification_with_invalid_credentials()
    {
        $user = User::create([
            'nama' => 'Test User Reset',
            'username' => 'userreset',
            'email' => 'userreset@steva.com',
            'no_hp' => '08999999999',
            'password' => Hash::make('oldpassword'),
            'role' => 'murid',
        ]);

        $response = $this->post(route('forgot.password.post'), [
            'username' => 'userreset',
            'email' => 'wrongemail@steva.com',
            'no_hp' => '08999999999',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['username']);
    }

    /**
     * Test reset password screen is session guarded.
     */
    public function test_reset_password_page_is_session_guarded()
    {
        // Accessing without session redirects to forgot password
        $response = $this->get(route('reset.password'));
        $response->assertRedirect(route('forgot.password'));
        $response->assertSessionHasErrors(['username']);
    }

    /**
     * Test successful password reset.
     */
    public function test_successful_password_reset()
    {
        $user = User::create([
            'nama' => 'Test User Reset',
            'username' => 'userreset',
            'email' => 'userreset@steva.com',
            'no_hp' => '08999999999',
            'password' => Hash::make('oldpassword'),
            'role' => 'murid',
        ]);

        // Put user ID in session
        $response = $this->withSession(['password_reset_user_id' => $user->id])
            ->post(route('reset.password.post'), [
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Password Anda berhasil diperbarui. Silakan login.');
        $response->assertSessionMissing('password_reset_user_id');

        // Verify password was updated in DB
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }
}
