<?php

declare(strict_types=1);

namespace App\Chat;

class Message
{
    public private(set) string $content {
        set(string $value) {
            $trimmed = trim($value);

            if ($trimmed === '') {
                throw new \InvalidArgumentException('content cannot be empty');
            }

            if (strlen($trimmed) > 2000) {
                throw new \InvalidArgumentException('content cannot exceed 2000 characters');
            }

            $this->content = $trimmed;
        }
    }

    public function __construct(
        string $content,
        public private(set) readonly int $userId,
        public private(set) readonly int $roomId,
    ) {
        $this->content = $content;
    }

    public function getContent(): string { return $this->content; }
    public function getUserId(): int     { return $this->userId; }
    public function getRoomId(): int     { return $this->roomId; }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'user_id' => $this->userId,
            'room_id' => $this->roomId,
        ];
    }
}
