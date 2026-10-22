<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PinController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\ProfileController;

// ──────────────────────────────────────────────
// Auth — publik (tanpa token)
// ──────────────────────────────────────────────
Route::post('/auth/check-email', [AuthController::class, 'checkEmail']);
Route::post('/auth/register',    [AuthController::class, 'register']);
Route::post('/auth/login',       [AuthController::class, 'login']);

// ──────────────────────────────────────────────
// Feed — publik (bisa lihat tanpa login)
// ──────────────────────────────────────────────
Route::get('/pins/feed',          [PinController::class, 'feed']);
Route::get('/pins/{pin}/related', [PinController::class, 'related']);

// ──────────────────────────────────────────────
// Semua endpoint di bawah membutuhkan token Sanctum
// ──────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    // Pins
    Route::post('/pins',              [PinController::class, 'store']);
    Route::post('/pins/{pin}/like',   [PinController::class, 'like']);
    Route::delete('/pins/{pin}/like', [PinController::class, 'unlike']);
    Route::post('/pins/{pin}/save',   [PinController::class, 'save']);
    Route::delete('/pins/{pin}/save', [PinController::class, 'unsave']);
    Route::get('/pins/saved',         [PinController::class, 'saved']);

    // Boards
    Route::get('/boards',                          [BoardController::class, 'index']);
    Route::post('/boards',                         [BoardController::class, 'store']);
    Route::delete('/boards/{board}',               [BoardController::class, 'destroy']);
    Route::post('/boards/{board}/pins/{pin}',      [BoardController::class, 'addPin']);
    Route::delete('/boards/{board}/pins/{pin}',    [BoardController::class, 'removePin']);

    // Profile
    Route::get('/me',   [ProfileController::class, 'show']);
    Route::patch('/me', [ProfileController::class, 'update']);
});
