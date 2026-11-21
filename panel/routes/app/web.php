<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Frontend\DashboardController;
use \App\Http\Controllers\Frontend\ApplyingForController;

/*----------app-----------*/

Route::group(['prefix' => '','middleware' => 'auth.web'], function () {
    //index
    Route::get('/', [DashboardController::class, 'index'])->name('app.dashboard');
    
     Route::get('/rahnama', [DashboardController::class, 'rahnama'])->name('app.rahnama');
     
     
    Route::get('/applying-for', [ApplyingForController::class, 'index'])->name('app.create-applying.all');
    Route::get('/create-applying', [ApplyingForController::class, 'create'])->name('app.create-applying.create');
    Route::post('/create-applying', [ApplyingForController::class, 'store'])->name('app.create-applying.store');
    Route::get('/answer-applying/{applyingFor}', [ApplyingForController::class, 'answer'])->name('app.applying.answer');
});
/*---------- app ------------*/
