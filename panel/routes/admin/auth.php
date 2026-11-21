<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\admin\LoginController;

/*----------Admins Authentications Routes-----------*/
Route::group(['prefix' => ''], function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('auth.admin.login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('auth.admin.login');
    Route::get('/logout', [LoginController::class, 'logout'])->name('auth.admin.logout');


});
