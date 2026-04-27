<?php

declare(strict_types=1);

namespace App\Models;

use App\Chat\RoomNameFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Room $room) {
            $room->slug = RoomNameFormatter::toSlug($room->name);
        });
    }

    /** @return HasMany<Message, $this> */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
