<?php

declare(strict_types=1);

namespace App\Chat;

class MessageValidator
{
    private array $fieldErrors = [];

    public function validate(array $payload): void
    {
        $this->fieldErrors = [];

        if (!array_key_exists('content', $payload)) {
            $this->fieldErrors['content'] = 'content is required';
            throw new ValidationException('content is required', $this->fieldErrors);
        }

        $content = trim((string) $payload['content']);

        if ($content === '') {
            $this->fieldErrors['content'] = 'content cannot be blank';
            throw new ValidationException('content cannot be blank', $this->fieldErrors);
        }

        if (strlen($content) > 2000) {
            $this->fieldErrors['content'] = 'content cannot exceed 2000 characters';
            throw new ValidationException('content cannot exceed 2000 characters', $this->fieldErrors);
        }

        if (!array_key_exists('room_id', $payload)) {
            $this->fieldErrors['room_id'] = 'room_id is required';
            throw new ValidationException('room_id is required', $this->fieldErrors);
        }

        if (!is_int($payload['room_id']) || $payload['room_id'] < 1) {
            $this->fieldErrors['room_id'] = 'room_id must be a positive integer';
            throw new ValidationException('room_id must be a positive integer', $this->fieldErrors);
        }
    }

    public function errors(): array
    {
        return $this->fieldErrors;
    }
}
