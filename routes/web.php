<?php

use App\Http\Controllers\Export\ExportPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/Auth/pdf/{record}', [ExportPdfController::class, 'pdf'])->name('auth.pdf')->middleware('auth');