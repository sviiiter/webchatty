<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Chat\MessageValidator;
use App\Chat\ValidationException;
use PHPUnit\Framework\TestCase;

class MessageValidatorTest extends TestCase
{
    private MessageValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new MessageValidator();
    }

    public function test_valid_payload_passes(): void
    {
        $this->validator->validate(['content' => 'Hello!', 'room_id' => 1]);

        // No exception = pass
        $this->assertTrue(true);
    }

    public function test_throws_when_content_key_is_missing(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('content');

        $this->validator->validate(['room_id' => 1]);
    }

    public function test_throws_when_content_is_empty_string(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('content');

        $this->validator->validate(['content' => '', 'room_id' => 1]);
    }

    public function test_throws_when_content_is_only_whitespace(): void
    {
        $this->expectException(ValidationException::class);

        $this->validator->validate(['content' => '   ', 'room_id' => 1]);
    }

    public function test_throws_when_content_exceeds_2000_characters(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('2000');

        $this->validator->validate(['content' => str_repeat('x', 2001), 'room_id' => 1]);
    }

    public function test_accepts_content_of_exactly_2000_characters(): void
    {
        $this->validator->validate(['content' => str_repeat('x', 2000), 'room_id' => 1]);

        $this->assertTrue(true);
    }

    public function test_throws_when_room_id_key_is_missing(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('room_id');

        $this->validator->validate(['content' => 'Hello']);
    }

    public function test_throws_when_room_id_is_not_a_positive_integer(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('room_id');

        $this->validator->validate(['content' => 'Hello', 'room_id' => 0]);
    }

    public function test_throws_when_room_id_is_negative(): void
    {
        $this->expectException(ValidationException::class);

        $this->validator->validate(['content' => 'Hello', 'room_id' => -5]);
    }

    public function test_errors_method_returns_all_accumulated_errors(): void
    {
        try {
            $this->validator->validate(['content' => '', 'room_id' => -1]);
        } catch (ValidationException $e) {
            // expected
        }

        $errors = $this->validator->errors();

        $this->assertIsArray($errors);
        $this->assertNotEmpty($errors);
    }
}
