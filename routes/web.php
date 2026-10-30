<?php

use App\Http\Controllers\PrintPrescriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prescriptions/{prescription}/print', PrintPrescriptionController::class)
    ->name('prescriptions.print')
    ->middleware(['auth:admin']);
