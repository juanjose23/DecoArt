<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Export\ExportPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/auth/login', function () {
    return view('auth.login');
});
Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');

Route::get('/Auth/pdf/{record}', [ExportPdfController::class, 'pdf'])->name('auth.pdf')->middleware('auth');