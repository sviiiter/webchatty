<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Chat\Message;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function test_can_be_constructed_with_valid_data(): void
    {
        $message = new Message(content: 'Hello!', userId: 1, roomId: 42);

        $this->assertInstanceOf(Message::class, $message);
    }

    public function test_get_content_returns_trimmed_value(): void
    {
        $message = new Message(content: '  Hello!  ', userId: 1, roomId: 42);

        $this->assertSame('Hello!', $message->getContent());
    }

    public function test_get_user_id_returns_correct_value(): void
    {
        $message = new Message(content: 'Hi', userId: 7, roomId: 1);

        $this->assertSame(7, $message->getUserId());
    }

    public function test_get_room_id_returns_correct_value(): void
    {
        $message = new Message(content: 'Hi', userId: 1, roomId: 99);

        $this->assertSame(99, $message->getRoomId());
    }

    public function test_to_array_contains_expected_keys(): void
    {
        $message = new Message(content: 'Hello', userId: 3, roomId: 5);

        $array = $message->toArray();

        $this->assertArrayHasKey('content', $array);
        $this->assertArrayHasKey('user_id', $array);
        $this->assertArrayHasKey('room_id', $array);
    }

    public function test_to_array_contains_correct_values(): void
    {
        $message = new Message(content: '  Trimmed  ', userId: 3, roomId: 5);

        $array = $message->toArray();

        $this->assertSame('Trimmed', $array['content']);
        $this->assertSame(3, $array['user_id']);
        $this->assertSame(5, $array['room_id']);
    }

    public function test_throws_when_content_is_empty_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('content');

        new Message(content: '', userId: 1, roomId: 1);
    }

    public function test_throws_when_content_is_only_whitespace(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Message(content: '   ', userId: 1, roomId: 1);
    }

    public function test_throws_when_content_exceeds_2000_characters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('2000');

        new Message(content: str_repeat('a', 2001), userId: 1, roomId: 1);
    }

    public function test_accepts_content_of_exactly_2000_characters(): void
    {
        $message = new Message(content: str_repeat('a', 2000), userId: 1, roomId: 1);

        $this->assertSame(2000, strlen($message->getContent()));
    }
}
