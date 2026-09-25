<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::prefix('properties')->group(function () {
        Route::get('/', [PropertyController::class, 'index']);
        Route::get('{id}', [PropertyController::class, 'show']);
        Route::post('/', [PropertyController::class, 'store']);
        Route::put('{id}', [PropertyController::class, 'update']);
        Route::delete('{id}', [PropertyController::class, 'destroy']);
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
});
