<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DocumentController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::prefix('auth')->group(function (): void {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function (): void {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Document Routes
Route::prefix('documents')->middleware('auth:api')->group(function (): void {
    Route::post('upload', [DocumentController::class, 'upload']);
    Route::get('/', [DocumentController::class, 'index']);
    Route::get('{uuid}', [DocumentController::class, 'show']);
    Route::get('{uuid}/download', [DocumentController::class, 'download']);
    Route::delete('{uuid}', [DocumentController::class, 'destroy']);
});
