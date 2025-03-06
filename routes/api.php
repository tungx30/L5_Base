<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('/admin/users', UserController::class);
});

// Route đăng ký cho user
Route::post('/register', [UserController::class, 'register']);


