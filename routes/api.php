<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('rooms', RoomController::class)->only(['index', 'store']);
Route::get('rooms/{room}/messages', [MessageController::class, 'index']);
Route::post('rooms/{room}/messages', [MessageController::class, 'store'])->middleware('auth:sanctum');
