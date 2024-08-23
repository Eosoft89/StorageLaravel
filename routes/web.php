<?php

use App\Http\Controllers\InfoController;
use Illuminate\Support\Facades\Route;

Route::get('/',[InfoController::class, 'index'] )->name('index');
Route::get('/create', [InfoController::class, 'create'])->name('create');
Route::post('/store', [InfoController::class, 'store'])->name('store');
