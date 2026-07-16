<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KatalogController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');