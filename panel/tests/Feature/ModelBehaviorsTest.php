<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelBehaviorsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_factory_matches_the_schema()
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->name);
        $this->assertNotNull($user->family);
        $this->assertNotNull($user->code_mli);
        $this->assertNotNull($user->phone);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_password_is_hidden_from_serialization()
    {
        $user = User::factory()->create();

        $this->assertArrayNotHasKey('password', $user->toArray());
        $this->assertArrayNotHasKey('remember_token', $user->toArray());
    }

    public function test_admin_is_soft_deletable()
    {
        $admin = Admin::create([
            'first_name' => 'حذف',
            'last_name' => 'نرم',
            'username' => 'todelete',
            'mobile' => '09121234567',
            'password' => 'hashed-value',
        ]);

        $admin->delete();

        $this->assertNull(Admin::find($admin->id));
        $this->assertNotNull(Admin::withTrashed()->find($admin->id)->deleted_at);
    }

    public function test_user_password_is_hashed_on_check()
    {
        $user = User::factory()->create(['password' => bcrypt('my-secret')]);

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('my-secret', $user->password));
        $this->assertFalse(\Illuminate\Support\Facades\Hash::check('other', $user->password));
    }
}
