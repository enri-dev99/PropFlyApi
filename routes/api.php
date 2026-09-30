<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {


    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::get('user', [AuthController::class, 'user'])
            ->middleware('auth:sanctum');
        Route::post('logout', [AuthController::class, 'logout'])
            ->middleware('auth:sanctum');
    });

    Route::prefix('properties')->group(function () {
        Route::get('/', [PropertyController::class, 'index']);
        Route::get('{property}', [PropertyController::class, 'show']);
        Route::post('/', [PropertyController::class, 'store'])
            ->middleware('auth:sanctum');
        Route::put('{property}', [PropertyController::class, 'update'])
            ->middleware('auth:sanctum');
        Route::delete('{property}', [PropertyController::class, 'destroy'])
            ->middleware('auth:sanctum');
    });
    Route::prefix('cities')->group(function () {
        Route::get('/', [CityController::class, 'index']);
        Route::get('{id}', [CityController::class, 'show']);
        Route::post('/', [CityController::class, 'store']);
        Route::put('{id}', [CityController::class, 'update']);
        Route::delete('{id}', [CityController::class, 'destroy']);
    });
    Route::prefix('localities')->group(function () {
        Route::get('/', [LocalityController::class, 'index']);
        Route::get('{id}', [LocalityController::class, 'show']);
        Route::post('/', [LocalityController::class, 'store']);
        Route::put('{id}', [LocalityController::class, 'update']);
        Route::delete('{id}', [LocalityController::class, 'destroy']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->middleware('auth:sanctum');

        Route::get('{user}', [UserController::class, 'show'])
            ->middleware('auth:sanctum');

        Route::put('{user}', [UserController::class, 'update'])
            ->middleware('auth:sanctum');

        Route::delete('{user}', [UserController::class, 'destroy'])
            ->middleware('auth:sanctum');
    });
});
