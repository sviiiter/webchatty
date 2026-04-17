<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::apiResource('rooms', RoomController::class)->only(['index', 'store']);
Route::get('rooms/{room}/messages', [MessageController::class, 'index']);
Route::post('rooms/{room}/messages', [MessageController::class, 'store'])->middleware('auth:sanctum');
