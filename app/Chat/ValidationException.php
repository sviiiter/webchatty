<?php

declare(strict_types=1);

namespace App\Chat;

class ValidationException extends \RuntimeException
{
    public function __construct(
        string $message,
        private readonly array $fieldErrors = [],
    ) {
        parent::__construct($message);
    }

    public function errors(): array
    {
        return $this->fieldErrors;
    }
}
