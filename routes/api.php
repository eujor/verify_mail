<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/validate', [\App\Http\Controllers\ValidationController::class, 'validateEmailViaApi'])->name('validate.api.mail');
