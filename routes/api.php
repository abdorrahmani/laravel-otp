<?php

use App\Http\Controllers\Api\auth\AuthController;
use App\Http\Controllers\Api\auth\AuthUserController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/tel-verification', 'verification');
});

Route::controller(AuthUserController::class)->group(function () {
        Route::get('/user', '__invoke');
        Route::post('/refresh-token', 'refresh');
        Route::post('/logout', 'logout');
})->middleware('auth:sanctum');

