<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


    // все роуты api версии v1
    Route::prefix('/v1')->group(function () {

        // Маршруты, не требующие аутентификации (без middleware)
        Route::get('/ping', function () {
            return response()->json(['message' => 'pong']);
        });

//        Route::group(['prefix' => 'profiles', 'middleware' => 'auth:sanctum'], function () {
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [App\Http\Controllers\UsersController::class, 'list'])->name('users.list');
//            Route::group(['prefix' => '{user}/chats', 'middleware' => ProfileBelongingToUserChecker::class], function () {
            Route::group(['prefix' => '{user}/chats'], function () {
                Route::get('/', [App\Http\Controllers\ChatsController::class, 'index'])->name('chats.index');
                Route::post('/', [App\Http\Controllers\ChatsController::class, 'createOrGet'])->name('chats.store');
                Route::delete('/{chat}', [App\Http\Controllers\ChatsController::class, 'destroy'])->name('chats.delete');
                Route::group(['prefix' => '{chatId}/messages'], function () {
                    Route::get('/', [App\Http\Controllers\MessagesController::class, 'list'])->name('messages.index');
                    Route::post('/', [App\Http\Controllers\MessagesController::class, 'create'])->name('messages.store');
                });
            });
        });


        // Маршруты, требующие аутентификации
        Route::middleware('auth:sanctum')->group(function () {

            //

        });
    });
