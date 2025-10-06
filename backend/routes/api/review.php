<?php

use App\Http\Api\V1\Review\CreateReviewController;
use App\Http\Api\V1\Review\DeleteReviewController;
use App\Http\Api\V1\Review\IndexReviewController;
use App\Http\Api\V1\Review\ShowReviewController;
use App\Http\Api\V1\Review\UpdateReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', IndexReviewController::class)->name('review.index');
        Route::post('/', CreateReviewController::class)->name('review.store');
        Route::get('/{id}', ShowReviewController::class)->name('review.show');
        Route::put('/{id}', UpdateReviewController::class)->name('review.update');
        Route::delete('/{id}', DeleteReviewController::class)->name('review.destroy');
    });
