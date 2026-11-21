<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(string $username = 'admin', string $password = 'password'): Admin
    {
        return Admin::create([
            'first_name' => 'محمد',
            'last_name' => 'مدیر',
            'username' => $username,
            'mobile' => '0900000000' . rand(1, 9),
            'password' => Hash::make($password),
        ]);
    }

    public function test_admin_login_form_is_public()
    {
        $this->get(route('auth.admin.login.form'))->assertOk();
    }

    public function test_admin_can_login_with_correct_credentials()
    {
        $admin = $this->createAdmin();

        $this->post(route('auth.admin.login'), [
            'username' => 'admin',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_login_rejects_wrong_password()
    {
        $this->createAdmin();

        $response = $this->from(route('auth.admin.login.form'))
            ->post(route('auth.admin.login'), [
                'username' => 'admin',
                'password' => 'wrong-password',
            ]);

        $response->assertRedirect(route('auth.admin.login.form'));
        $response->assertSessionHas('login-failed');
        $this->assertGuest('admin');
    }

    public function test_admin_pages_require_authentication()
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('auth.admin.login.form'));
    }

    public function test_admin_can_open_all_panel_pages()
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'admin');

        $pages = [
            '/admin/dashboard',
            '/admin/users',
            '/admin/admins',
            '/admin/applying-for',
            '/admin/profile',
        ];

        foreach ($pages as $page) {
            $this->get($page)->assertOk();
        }
    }
}
