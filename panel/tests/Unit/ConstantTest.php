<?php

namespace Tests\Unit;

use App\Constants\Constant;
use PHPUnit\Framework\TestCase;

class ConstantTest extends TestCase
{
    public function test_request_image_path_points_to_web_root_uploads()
    {
        $expected = 'uploads'
            . DIRECTORY_SEPARATOR . 'request'
            . DIRECTORY_SEPARATOR . 'image'
            . DIRECTORY_SEPARATOR;

        $this->assertEquals($expected, Constant::REQUEST_IMAGE_PATH);
    }

    public function test_admin_status_values()
    {
        $this->assertEquals('active', Constant::ACTIVE);
        $this->assertEquals('in-active', Constant::IN_ACTIVE);
    }
}
