<?php

use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\MenuController;
use Illuminate\Support\Facades\Route;

// Rute halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute halaman menu
Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::get('/', function () {
    return view('welcome');
});
