<?php

use App\Http\Api\V1\Option\CreateOptionController;
use App\Http\Api\V1\Option\DeleteOptionController;
use App\Http\Api\V1\Option\IndexOptionController;
use App\Http\Api\V1\Option\UpdateOptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', IndexOptionController::class)->name('option.index');
        Route::post('/', CreateOptionController::class)->name('option.create');
        Route::put('/{id}', UpdateOptionController::class)->name('room.update')->where('id', '[0-9]+');
        Route::delete('/{id}', DeleteOptionController::class)->name('room.delete')->where('id', '[0-9]+');
    });
