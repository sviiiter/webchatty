<?php

declare(strict_types=1);

namespace App\Chat;

class RoomNameFormatter
{
    public static function toSlug(string $name): string
    {
        $trimmed = trim($name);

        if ($trimmed === '') {
            throw new \InvalidArgumentException('name cannot be empty');
        }

        return (string) preg_replace('/\s+/', '-', strtolower($trimmed));
    }
}
