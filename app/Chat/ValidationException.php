<?php

declare(strict_types=1);

namespace App\Chat;

class ValidationException extends \RuntimeException
{
    /**
     * @param array<string, string> $fieldErrors
     */
    public function __construct(
        string $message,
        private readonly array $fieldErrors = [],
    ) {
        parent::__construct($message);
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->fieldErrors;
    }
}
