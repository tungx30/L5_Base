<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'AdminMiddleware'])->group(function () {
    Route::apiResource('/admin/users', UserController::class);
});

Route::post('/register', [UserController::class, 'register']);

