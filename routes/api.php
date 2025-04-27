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
            Route::get('/', [App\Http\Controllers\UsersController::class, 'list']);
            Route::post('/chats', [App\Http\Controllers\ChatsController::class, 'createOrGet']);
//            Route::group(['prefix' => '{user}/chats', 'middleware' => ProfileBelongingToUserChecker::class], function () {
            Route::group(['prefix' => '{user}/chats'], function () {
                Route::get('/', [App\Http\Controllers\ChatsController::class, 'listForUser']);
                Route::group(['prefix' => '{chatId}/messages'], function () {
                    Route::get('/', [App\Http\Controllers\MessagesController::class, 'list']);
                    Route::post('/', [App\Http\Controllers\MessagesController::class, 'create']);
                });
            });
        });


        // Маршруты, требующие аутентификации
        Route::middleware('auth:sanctum')->group(function () {

            //

        });
    });
