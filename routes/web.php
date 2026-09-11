<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\mainControler;
use App\Http\Middleware\CheckIsLogged;
use App\Http\Middleware\CheckIsNotLogged;
use Illuminate\Support\Facades\Route;

// auth routes - user not logged
Route::middleware(CheckIsNotLogged::class)->group(function(){
    Route::get('login', [authController::class, 'login']);
    Route::post('loginSubmit', [authController::class, 'loginSubmit']);
    });
    
    // auth routes - user logged
Route::middleware([CheckIsLogged::class])->group(function(){
    Route::get('/', [mainControler::class, 'index'])->name('home');
    Route::get('/newNote', [mainControler::class, 'newNote'])->name('new');
    Route::get('logout', [authController::class, 'logout'])->name('logout');
});