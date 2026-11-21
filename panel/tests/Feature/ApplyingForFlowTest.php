<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ApplyingFor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApplyingForFlowTest extends TestCase
{
    use RefreshDatabase;

    private string $testPublicDir;

    protected function setUp(): void
    {
        parent::setUp();

        // کنترلرها فایل‌ها را در public_path می‌نویسند؛ در تست به یک پوشه موقت ریدایرکت می‌کنیم
        $this->testPublicDir = storage_path('framework/testing/public_html');
        $this->app->instance('path.public', $this->testPublicDir);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->testPublicDir)) {
            \File::deleteDirectory($this->testPublicDir);
        }

        parent::tearDown();
    }

    private function createUser(): User
    {
        return User::create([
            'name' => 'کاربر',
            'family' => 'تستی',
            'code_mli' => '0012345678',
            'phone' => '09120000999',
            'password' => Hash::make('secret123'),
        ]);
    }

    public function test_request_requires_title_and_file()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'web')
            ->post(route('app.create-applying.store'), [])
            ->assertSessionHasErrors(['title', 'file']);
    }

    public function test_user_can_submit_request_with_image()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'web')
            ->post(route('app.create-applying.store'), [
                'title' => 'درخواست تست',
                'file' => UploadedFile::fake()->image('request.png'),
            ])
            ->assertRedirect(route('app.create-applying.all'));

        $this->assertDatabaseHas('applying_fors', [
            'title' => 'درخواست تست',
            'user_id' => $user->id,
            'full_name' => 'کاربر تستی',
            'code_mli' => '0012345678',
        ]);

        $record = ApplyingFor::first();
        $this->assertNotNull($record->image);
        $this->assertFileExists($this->testPublicDir . '/uploads/request/image/' . $record->image);
    }

    public function test_admin_can_answer_a_request_with_image()
    {
        $user = $this->createUser();

        $record = ApplyingFor::create([
            'title' => 'درخواست پاسخ‌دنیافته',
            'image' => 'req.png',
            'full_name' => 'کاربر تستی',
            'code_mli' => '0012345678',
            'phone' => '09120000999',
            'user_id' => $user->id,
        ]);

        $admin = Admin::create([
            'first_name' => 'محمد',
            'last_name' => 'مدیر',
            'username' => 'admin',
            'mobile' => '09000000001',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.create-applying.answer.store', $record), [
                'description' => 'پاسخ مدیر به درخواست',
                'javab' => UploadedFile::fake()->image('answer.png'),
            ])
            ->assertRedirect(route('admin.create-applying.all'));

        $record->refresh();
        $this->assertEquals('پاسخ مدیر به درخواست', $record->description);
        $this->assertNotNull($record->javab);
        $this->assertFileExists($this->testPublicDir . '/uploads/request/image/' . $record->javab);

        // پاسخ باید برای کاربر در فهرست درخواست‌ها قابل مشاهده باشد
        $this->actingAs($user, 'web')
            ->get(route('app.create-applying.all'))
            ->assertOk()
            ->assertSee('درخواست پاسخ‌دنیافته');
    }

    public function test_answer_requires_description_and_image()
    {
        $record = ApplyingFor::create([
            'title' => 'درخواست بدون پاسخ',
            'image' => 'req.png',
            'full_name' => 'کاربر تستی',
            'code_mli' => '0012345678',
            'phone' => '09120000999',
            'user_id' => 1,
        ]);

        $admin = Admin::create([
            'first_name' => 'محمد',
            'last_name' => 'مدیر',
            'username' => 'admin',
            'mobile' => '09000000002',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.create-applying.answer.store', $record), [])
            ->assertSessionHasErrors(['description', 'javab']);
    }
}
