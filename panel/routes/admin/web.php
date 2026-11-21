<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\DashboardController;
use \App\Http\Controllers\Admin\AdminsController;
use \App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ApplyingForController;


/*----------admin-----------*/

Route::group(['prefix' => '', 'middleware' => 'auth.admin'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    //Profile
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('admin.profile.index');
        Route::post('/update', [ProfileController::class, 'update'])->name('admin.profile.update');

    });
    //Admins
    Route::group(['prefix' => 'admins'], function () {
        Route::get('/', [AdminsController::class, 'index'])->name('admin.admins.all');
        Route::get('/create', [AdminsController::class, 'create'])->name('admin.admins.create');
        Route::post('/store', [AdminsController::class, 'store'])->name('admin.admins.store');
        Route::get('/edit/{admin}', [AdminsController::class, 'edit'])->name('admin.admins.edit');
        Route::post('/update/{admin}', [AdminsController::class, 'update'])->name('admin.admins.update');
        Route::get('/logical-deletion/{admin}', [AdminsController::class, 'logicalDeletion'])->name('admin.admins.logicalDeletion');
    });
    // users
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UsersController::class, 'index'])->name('admin.users.all');
        Route::get('/create', [AdminsController::class, 'create'])->name('admin.users.create');
        Route::get('/delete/{user}', [UsersController::class, 'delete'])->name('admin.users.delete');

    });
    Route::get('/applying-for', [ApplyingForController::class, 'index'])->name('admin.create-applying.all');
    Route::get('/applying-for/answer/{applyingFor}', [ApplyingForController::class, 'answer'])->name('admin.create-applying.answer');
    Route::post('/applying-for/answer/store/{applyingFor}', [ApplyingForController::class, 'store'])->name('admin.create-applying.answer.store');




});
/*---------- app ------------*/
