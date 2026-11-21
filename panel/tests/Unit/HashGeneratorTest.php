<?php

namespace Tests\Unit;

use App\Helpers\Hash\HashGenerator;
use PHPUnit\Framework\TestCase;

class HashGeneratorTest extends TestCase
{
    public function test_make_returns_hex_string_of_requested_length()
    {
        $token = HashGenerator::make(10);

        $this->assertEquals(10, mb_strlen($token));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{10}$/', $token);
    }

    public function test_make_supports_other_even_lengths()
    {
        $this->assertEquals(16, strlen(HashGenerator::make(16)));
        $this->assertEquals(4, strlen(HashGenerator::make(4)));
    }

    public function test_make_generates_unique_tokens()
    {
        $tokens = [];

        for ($i = 0; $i < 100; $i++) {
            $tokens[] = HashGenerator::make(10);
        }

        $this->assertCount(100, array_unique($tokens));
    }
}
