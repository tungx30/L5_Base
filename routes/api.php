<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/admin/login', [AdminController::class, 'login']);

Route::middleware('AdminMiddleware')->group(function () {
    Route::post('/admin/logout', [AdminController::class, 'logout']);
    Route::get('/admin/profile', [AdminController::class, 'profile']);
});

Route::middleware('AdminMiddleware')->group(function () {
    Route::apiResource('/admin/users', UserController::class);
});

Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);

Route::middleware('UserMiddleware')->group(function () {
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::post('/user/logout', [UserController::class, 'logout']);
});
