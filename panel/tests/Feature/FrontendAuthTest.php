<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FrontendAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard()
    {
        $this->get('/')->assertRedirect(route('auth.app.login.form'));
    }

    public function test_login_and_register_forms_are_public()
    {
        $this->get(route('auth.app.login.form'))->assertOk();
        $this->get(route('auth.app.register.form'))->assertOk();
    }

    public function test_user_can_register_and_is_logged_in()
    {
        $data = [
            'name' => 'تست',
            'family' => 'کاربر',
            'phone' => '09120000001',
            'code_mli' => '0012345678',
            'password' => 'secret123',
        ];

        $this->post(route('auth.app.register'), $data)
            ->assertRedirect(route('app.dashboard'));

        $this->assertDatabaseHas('users', ['phone' => '09120000001']);
        $this->assertAuthenticatedAs(User::where('phone', '09120000001')->first(), 'web');
    }

    public function test_registration_rejects_duplicate_phone()
    {
        User::create([
            'name' => 'اول',
            'family' => 'قبلی',
            'code_mli' => '0012345679',
            'phone' => '09120000002',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->from(route('auth.app.register.form'))
            ->post(route('auth.app.register'), [
                'name' => 'دوم',
                'family' => 'جدید',
                'phone' => '09120000002',
                'code_mli' => '0012345680',
                'password' => 'secret123',
            ]);

        $response->assertRedirect(route('auth.app.register.form'));
        $response->assertSessionHasErrors('phone');
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::create([
            'name' => 'لاگین',
            'family' => 'تست',
            'code_mli' => '0012345681',
            'phone' => '09120000003',
            'password' => Hash::make('secret123'),
        ]);

        $this->post(route('auth.app.login'), [
            'phone' => '09120000003',
            'password' => 'secret123',
        ])->assertRedirect(route('app.dashboard'));

        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_login_with_wrong_password_fails()
    {
        User::create([
            'name' => 'لاگین',
            'family' => 'تست',
            'code_mli' => '0012345682',
            'phone' => '09120000004',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->from(route('auth.app.login.form'))
            ->post(route('auth.app.login'), [
                'phone' => '09120000004',
                'password' => 'wrong-password',
            ]);

        $response->assertRedirect(route('auth.app.login.form'));
        $response->assertSessionHas('login-failed');
        $this->assertGuest('web');
    }
}
