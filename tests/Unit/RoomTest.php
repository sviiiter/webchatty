<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Chat\Room;
use App\Chat\RoomNameFormatter;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RoomTest extends TestCase
{
    public function test_can_be_constructed_with_a_name(): void
    {
        $room = new Room(name: 'General');

        $this->assertInstanceOf(Room::class, $room);
    }

    public function test_get_name_returns_the_original_name(): void
    {
        $room = new Room(name: 'General Chat');

        $this->assertSame('General Chat', $room->getName());
    }

    public function test_get_slug_returns_lowercased_hyphenated_name(): void
    {
        $room = new Room(name: 'General Chat');

        $this->assertSame('general-chat', $room->getSlug());
    }

    public function test_get_slug_strips_leading_and_trailing_spaces(): void
    {
        $room = new Room(name: '  Lobby  ');

        $this->assertSame('lobby', $room->getSlug());
    }

    public function test_two_rooms_with_same_name_produce_identical_slugs(): void
    {
        $room1 = new Room(name: 'Dev Talk');
        $room2 = new Room(name: 'Dev Talk');

        $this->assertSame($room1->getSlug(), $room2->getSlug());
    }

    public function test_throws_when_name_is_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('name');

        new Room(name: '');
    }

    public function test_throws_when_name_is_only_whitespace(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Room(name: '   ');
    }

    // RoomNameFormatter static helper tests

    public function test_formatter_converts_spaces_to_hyphens(): void
    {
        $this->assertSame('general-chat', RoomNameFormatter::toSlug('General Chat'));
    }

    public function test_formatter_lowercases_the_result(): void
    {
        $this->assertSame('backend', RoomNameFormatter::toSlug('BACKEND'));
    }

    public function test_formatter_trims_whitespace_before_slugifying(): void
    {
        $this->assertSame('design', RoomNameFormatter::toSlug('  Design  '));
    }

    public function test_formatter_collapses_multiple_spaces_into_single_hyphen(): void
    {
        $this->assertSame('a-b', RoomNameFormatter::toSlug('a  b'));
    }

    public function test_formatter_throws_on_empty_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('name');

        RoomNameFormatter::toSlug('');
    }
}
