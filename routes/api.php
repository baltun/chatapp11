<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


    // все роуты api версии v1
    Route::prefix('/v1')->group(function () {

        // Маршруты, не требующие аутентификации (без middleware)
        Route::get('/ping', function () {
            return response()->json(['message' => 'pong']);
        });
        Route::group(['prefix' => 'auth'], function () {
            Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('auth.register');
            Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('auth.login');
            Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('auth.logout');
        });

        Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
            Route::get('/', [App\Http\Controllers\UsersController::class, 'list'])->name('users.list');
            Route::group(['prefix' => '{user}/chats', 'middleware' => ['permissions']], function () {
                Route::get('/', [App\Http\Controllers\ChatsController::class, 'index'])->name('chats.index');
                Route::post('/', [App\Http\Controllers\ChatsController::class, 'createOrGet'])->name('chats.store');
                Route::delete('/{chat}', [App\Http\Controllers\ChatsController::class, 'destroy'])->name('chats.delete');
                Route::group(['prefix' => '{chat}/messages'], function () {
                    Route::get('/', [App\Http\Controllers\MessagesController::class, 'list'])->name('messages.index');
                    Route::post('/', [App\Http\Controllers\MessagesController::class, 'store'])->name('messages.store');
                });
            });
        });


        // Маршруты, требующие аутентификации
        Route::middleware('auth:sanctum')->group(function () {

            //

        });
    });
