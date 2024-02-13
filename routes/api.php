<?php

use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::get('/', fn() => response()->json(['Version' => 1.0, 'Message' => 'OK']));

    Route::apiResource('users', UserController::class);
    Route::apiResource('permissions', PermissionController::class);
});
