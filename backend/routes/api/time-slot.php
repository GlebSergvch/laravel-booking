<?php

use App\Http\Api\V1\TimeSlot\CreateTimeSlotController;
use App\Http\Api\V1\TimeSlot\DeleteTimeSlotController;
use App\Http\Api\V1\TimeSlot\IndexTimeSlotController;
use App\Http\Api\V1\TimeSlot\ShowTimeSlotController;
use App\Http\Api\V1\TimeSlot\UpdateAvailabilityController;
use App\Http\Api\V1\TimeSlot\UpdateTimeSlotController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', IndexTimeSlotController::class)->name('time-slots.index');
        Route::post('/', CreateTimeSlotController::class)->name('time-slot.store');
        Route::get('/{id}', ShowTimeSlotController::class)->name('time-slots.show');
        Route::put('/{id}', UpdateTimeSlotController::class)->name('time-slots.update');
        Route::delete('/{id}', DeleteTimeSlotController::class)->name('time-slots.destroy');
        Route::patch('/{id}/availability', UpdateAvailabilityController::class)->name('time-slots.update-availability');
    });
