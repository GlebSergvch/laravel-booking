<?php

use App\Http\Api\V1\Hotel\CreateHotelController;
use App\Http\Api\V1\Hotel\DeleteHotelController;
use App\Http\Api\V1\Hotel\IndexHotelController;
use App\Http\Api\V1\Hotel\ShowHotelController;
use App\Http\Api\V1\Hotel\UpdateHotelController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', IndexHotelController::class)->name('hotel.index');
        Route::post('/', CreateHotelController::class)->name('hotel.create');
        Route::get('/{id}', ShowHotelController::class)->name('hotel.show')->where('id', '[0-9]+');
        Route::put('/{id}', UpdateHotelController::class)->name('hotel.update')->where('id', '[0-9]+');
        Route::delete('/{id}', DeleteHotelController::class)->name('hotel.delete')->where('id', '[0-9]+');
    });
