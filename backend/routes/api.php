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
//        Route::post('/hotel', \App\Http\Api\V1\Hotel\CreateHotelController::class);
    });
    Route::post('/hotel', \App\Http\Api\V1\Hotel\CreateHotelController::class);
    Route::put('/signin', SigninController::class);//->middleware([ProtectDevBaseAuth::class]);
//    Route::post('/register', RegistrationController::class);//->middleware([ProtectDevBaseAuth::class]);
});
