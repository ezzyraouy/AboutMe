<?php

use App\Http\Controllers\Frontend\IndexController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')
    ->as('admin.')
    ->group(function () {
        require base_path('routes/admin.php');
    });

Route::get('/', [IndexController::class, 'index'])->name('frontend.index');

Auth::routes();
Auth::routes(['register' => false]);

// Route::get('/dashboard/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
