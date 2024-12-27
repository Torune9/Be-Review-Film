<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CastController;
use App\Http\Controllers\API\CastMovieController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ReviewsController;
use App\Http\Controllers\API\RoleController;
use App\Models\Roles;
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


Route::prefix('v1')->group(function (){

    Route::prefix('auth')->group(function(){

        Route::post('register',[AuthController::class,'register']);
        Route::post('login',[AuthController::class,'login']);

        Route::middleware('auth_api')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
            Route::post('generate-otp-code',[AuthController::class,'generateOTP']);
            Route::post('verifikasi-akun',[AuthController::class,'verification_account']);
        });
    });

    Route::apiResource('cast',CastController::class);

    Route::apiResource('genre',GenreController::class);

    Route::apiResource('movie',MovieController::class);

    Route::apiResource('role',RoleController::class);

    Route::apiResource('cast-movie',CastMovieController::class);

    Route::post('profile',[ProfileController::class,'createOrUpdateProfile']);

    Route::post('review',[ReviewsController::class,'createOrUpdateReview']);

});



