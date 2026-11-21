<?php

namespace Tests\Feature;

use App\Models\ApplyingFor;
use App\Presenters\Web\ApplyingFor\ApplyingForPresenter;
use Tests\TestCase;

class PresentersTest extends TestCase
{
    public function test_web_present_returns_the_registered_presenter()
    {
        $record = new ApplyingFor(['image' => null]);

        $this->assertInstanceOf(ApplyingForPresenter::class, $record->webPresent());
    }

    public function test_blank_image_falls_back_to_placeholder()
    {
        $url = (new ApplyingFor(['image' => null]))->webPresent()->image();

        $this->assertStringContainsString('blank.png', $url);
    }

    public function test_image_url_uses_upload_path_with_forward_slashes()
    {
        $url = (new ApplyingFor(['image' => 'abc123.png']))->webPresent()->image();

        // نکته: ثابت REQUEST_IMAGE_PATH با DIRECTORY_SEPARATOR تمام می‌شود و presenter
        // خودش هم یک / اضافه می‌کند؛ خروجی دارای دابل‌اسلش است اما سرورها آن را نرمال می‌کنند.
        $this->assertStringContainsString('uploads/request/image', $url);
        $this->assertStringContainsString('abc123.png', $url);
        $this->assertStringNotContainsString('\\', $url);
    }

    public function test_answer_url_uses_upload_path_with_forward_slashes()
    {
        $url = (new ApplyingFor(['javab' => 'ans123.png']))->webPresent()->javab();

        $this->assertStringContainsString('uploads/request/image', $url);
        $this->assertStringContainsString('ans123.png', $url);
        $this->assertStringNotContainsString('\\', $url);
    }

    public function test_missing_web_presenter_throws_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('presenter not fount!');

        $model = new ApplyingFor();
        $reflection = new \ReflectionProperty($model, 'webPresenter');
        $reflection->setAccessible(true);
        $reflection->setValue($model, null);

        $model->webPresent();
    }
}
