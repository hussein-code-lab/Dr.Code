<?php

use App\Http\Controllers\OnlineBookingController;
use App\Http\Controllers\PrintPrescriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OnlineBookingController::class, 'index'])
    ->name('home');

Route::post('/appointments/book', [OnlineBookingController::class, 'store'])
    ->middleware(['throttle:10,1'])
    ->name('appointments.book');

Route::get('/appointments/confirmation/{appointment}', [OnlineBookingController::class, 'confirmation'])
    ->middleware(['signed'])
    ->name('appointments.confirmation');

Route::get('/appointments/track', [OnlineBookingController::class, 'track'])
    ->name('appointments.track');

Route::post('/appointments/{appointment}/cancel', [OnlineBookingController::class, 'cancel'])
    ->middleware(['signed'])
    ->name('appointments.cancel');

Route::get('/prescriptions/{prescription}/print', PrintPrescriptionController::class)
    ->name('prescriptions.print')
    ->middleware(['auth:admin']);
