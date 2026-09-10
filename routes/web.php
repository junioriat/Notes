<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\mainControler;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// auth routes
Route::get('login', [authController::class, 'login']);
Route::post('loginSubmit', [authController::class, 'loginSubmit']);
Route::get('logout', [authController::class, 'logout']);
