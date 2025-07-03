<?php

use App\Http\Api\V1\BookingObject\CreateBookingObjectController;
use App\Http\Api\V1\BookingObject\DeleteBookingObjectController;
use App\Http\Api\V1\BookingObject\IndexBookingObjectController;
use App\Http\Api\V1\BookingObject\ShowBookingObjectController;
use App\Http\Api\V1\BookingObject\UpdateBookingObjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', IndexBookingObjectController::class)->name('booking-objects.index');
        Route::post('/', CreateBookingObjectController::class)->name('booking-objects.store');
        Route::get('/{id}', ShowBookingObjectController::class)->name('booking-objects.show');
        Route::put('/{id}', UpdateBookingObjectController::class)->name('booking-objects.update');
        Route::delete('/{id}', DeleteBookingObjectController::class)->name('booking-objects.destroy');
    });
