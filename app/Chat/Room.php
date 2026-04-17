<?php

declare(strict_types=1);

namespace App\Chat;

class Room
{
    public private(set) readonly string $slug;

    public function __construct(
        public private(set) readonly string $name,
    ) {
        $this->slug = RoomNameFormatter::toSlug($name);
    }

    public function getName(): string { return $this->name; }
    public function getSlug(): string { return $this->slug; }
}
