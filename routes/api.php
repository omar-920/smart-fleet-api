<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register/shop', [AuthController::class, 'registerShop']);
Route::post('/register/driver', [AuthController::class, 'registerDriver']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/orders', [OrderController::class,'store']);
    Route::get('/orders', [OrderController::class,'index']);
    Route::post('/order/{id}/accept' , [OrderController::class,'acceptOrder']);
    Route::post('/order/{id}/deliver' , [OrderController::class,'deliverOrder']);
    Route::post('/send-otp', [\App\Http\Controllers\Api\VerificationController::class, 'sendOTP']);
    Route::post('/verify-otp', [\App\Http\Controllers\Api\VerificationController::class, 'verifyOTP']);

    Route::post('/send-otp/shop', [\App\Http\Controllers\Api\ShopVerificationController::class, 'sendOTP']);
    Route::post('/verify-otp/shop', [\App\Http\Controllers\Api\ShopVerificationController::class, 'verifyOTP']);
});
