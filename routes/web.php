<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/batch/validation', [\App\Http\Controllers\ValidationController::class, 'validateBatchEmails'])->name('batchvalidate');