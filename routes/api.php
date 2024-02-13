<?php

use App\Http\Controllers\Api\Auth\AuthApiController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::get('/', fn() => response()->json(['Version' => 1.0, 'Message' => 'OK']));
    Route::post('auth/token', [AuthApiController::class, 'authToken']);

    Route::middleware(['auth:sanctum'])->group(function() {
        Route::prefix('auth')->group(function() {
            Route::post('logout', [AuthApiController::class, 'logoutToken']);
            Route::post('me', [AuthApiController::class, 'authMe']);
        });

        Route::apiResource('users', UserController::class);
        Route::apiResource('permissions', PermissionController::class);
    });
});
