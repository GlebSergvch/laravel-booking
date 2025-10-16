<?php

use App\Http\Api\V1\User\SigninController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'], static function () {
    Route::group(['middleware' => 'auth:api'], static function () {
    });

    Route::post('/register', [SigninController::class, 'register']);
    Route::post('/login', [SigninController::class, 'login']);
    Route::post('/logout', [SigninController::class, 'logout']);

    Route::prefix('hotel')
        ->name('hotel.')
        ->group(__DIR__.'/api/hotel.php');

    Route::prefix('room')
        ->name('room.')
        ->group(__DIR__.'/api/room.php');

    Route::prefix('option')
        ->name('option.')
        ->group(__DIR__.'/api/option.php');

    Route::prefix('booking-objects')
        ->name('booking-objects.')
        ->group(__DIR__.'/api/booking-objects.php');

    Route::prefix('time-slot')
        ->name('time-slot.')
        ->group(__DIR__.'/api/time-slot.php');

    Route::prefix('review')
        ->name('review')
        ->group(__DIR__.'/api/review.php');

    Route::prefix('booking')
        ->name('booking')
        ->group(__DIR__.'/api/booking.php');
});
