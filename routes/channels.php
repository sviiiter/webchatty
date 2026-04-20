<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Broadcast::channel('chat.{roomId}', function ($user) {
    return $user !== null;
});
