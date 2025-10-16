<?php

use App\Http\Api\V1\Booking\CreateBookingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/', CreateBookingController::class)->name('booking.store');
    });
