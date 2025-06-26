<?php

use App\Http\Api\V1\Hotel\CreateHotelController;
use App\Http\Api\V1\Hotel\DeleteHotelController;
use App\Http\Api\V1\Hotel\IndexHotelController;
use App\Http\Api\V1\Hotel\ShowHotelController;
use App\Http\Api\V1\Hotel\UpdateHotelController;
use App\Http\Api\V1\Room\CreateRoomController;
use App\Http\Api\V1\Room\DeleteRoomController;
use App\Http\Api\V1\Room\IndexRoomController;
use App\Http\Api\V1\Room\ShowRoomController;
use App\Http\Api\V1\Room\UpdateRoomController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->group(function () {
//        Route::get('/', IndexRoomController::class)->name('room.index');
        Route::post('/', CreateRoomController::class)->name('room.create');
        Route::get('/', IndexRoomController::class)->name('room.index');
        Route::get('/{id}', ShowRoomController::class)->name('room.show')->where('id', '[0-9]+');
        Route::put('/{id}', UpdateRoomController::class)->name('room.update')->where('id', '[0-9]+');
        Route::delete('/{id}', DeleteRoomController::class)->name('room.delete')->where('id', '[0-9]+');
//        Route::get('/{id}', ShowHotelController::class)->name('hotel.show')->where('id', '[0-9]+');
//        Route::put('/{id}', UpdateHotelController::class)->name('hotel.update')->where('id', '[0-9]+');
//        Route::delete('/{id}', DeleteHotelController::class)->name('hotel.delete')->where('id', '[0-9]+');
    });
