<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\AdminController;
use App\Http\Controllers\v1\SellerController;
use App\Http\Controllers\v1\CountryController;

Route::group(['middleware' => ['api', 'checkApiPassword']], function ($router) {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::group(['prefix' => 'admin'], function () {
            Route::post('/login', [AdminController::class, 'login']);
            Route::post('/register', [AdminController::class, 'register']);
            Route::post('/refresh', [AdminController::class, 'refresh']);
            Route::get('/profile', [AdminController::class, 'profile']);
            Route::post('/logout', [AdminController::class, 'logout']);
        });

        Route::group(['prefix' => 'seller'], function () {
            Route::post('/login', [SellerController::class, 'login']);
            Route::post('/register', [SellerController::class, 'register']);
            Route::post('/refresh', [SellerController::class, 'refresh']);
            Route::get('/profile', [SellerController::class, 'profile']);
            Route::post('/logout', [SellerController::class, 'logout']);
        });
    });

    Route::get('/countries', [CountryController::class, 'index']);

});

Route::get('/test', function () {
    return response()->json(['message' => 'Hello World!']);
});
