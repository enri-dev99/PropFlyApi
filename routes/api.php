<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {


    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('user', [AuthController::class, 'user']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::prefix('properties')->group(function () {
        Route::get('/', [PropertyController::class, 'index']);
        Route::get('{property}', [PropertyController::class, 'show']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [PropertyController::class, 'store']);
            Route::put('{property}', [PropertyController::class, 'update']);
            Route::delete('{property}', [PropertyController::class, 'destroy']);
        });
    });
    Route::prefix('countries')->group(function () {
        Route::get('/', [CountryController::class, 'index']);
        Route::get('{id}', [CountryController::class, 'show']);
        Route::middleware(['auth:sanctum', 'admin'])->group(function () {
            Route::post('/', [CountryController::class, 'store']);
            Route::put('{id}', [CountryController::class, 'update']);
            Route::delete('{id}', [CountryController::class, 'destroy']);
        });
    });
    Route::prefix('cities')->group(function () {
        Route::get('/', [CityController::class, 'index']);
        Route::get('{id}', [CityController::class, 'show']);
        Route::middleware(['auth:sanctum', 'admin'])->group(function () {
            Route::post('/', [CityController::class, 'store']);
            Route::put('{id}', [CityController::class, 'update']);
            Route::delete('{id}', [CityController::class, 'destroy']);
        });
    });
    Route::prefix('localities')->group(function () {
        Route::get('/', [LocalityController::class, 'index']);
        Route::get('{id}', [LocalityController::class, 'show']);
        Route::middleware(['auth:sanctum', 'admin'])->group(function () {
            Route::post('/', [LocalityController::class, 'store']);
            Route::put('{id}', [LocalityController::class, 'update']);
            Route::delete('{id}', [LocalityController::class, 'destroy']);
        });
    });

    Route::prefix('users')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('{user}', [UserController::class, 'show']);
            Route::put('{user}', [UserController::class, 'update']);
            Route::delete('{user}', [UserController::class, 'destroy']);
            Route::middleware('admin')->group(function () {
                Route::patch('{user}/role', [
                    UserController::class,
                    'updateRole',
                ]);
            });
        });
    });
});
