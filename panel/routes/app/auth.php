<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\app\LoginController;
use App\Http\Controllers\auth\app\RegisterController;

/*----------App Authentications Routes-----------*/
Route::group(['prefix' => ''], function () {

    //login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('auth.app.login.form');

    Route::post('/login', [LoginController::class, 'login'])->name('auth.app.login');
    Route::get('/logout', [LoginController::class, 'logout'])->name('auth.app.logout');

    //register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('auth.app.register.form');
    Route::post('/register', [RegisterController::class, 'register'])->name('auth.app.register');



});
