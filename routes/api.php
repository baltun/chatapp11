<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
    // все роуты api версии v1
    Route::prefix('/v1')->group(function () {

        // Маршруты, не требующие аутентификации (без middleware)
        Route::get('/ping', function () {
            return response()->json(['message' => 'pong']);
        });

        Route::get('/users', [App\Http\Controllers\UsersController::class, 'list']);
        // Маршруты, требующие аутентификации
        Route::middleware('auth:sanctum')->group(function () {

            //

        });
    });
