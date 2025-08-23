<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatroomController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserDeviceTokenController;
use App\Http\Controllers\Api\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);
Route::post('verify', [RegisterController::class, 'verify']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('profile/me', [UserProfileController::class, 'getLoggedUserProfile']);
    Route::post('users/search', [UserController::class, 'search']);
    // Route::apiResource('users', UserController::class); not needed for now
    Route::apiResource('chatrooms', ChatroomController::class);
    Route::apiResource('messages', MessageController::class);
    Route::apiResource('user-device-tokens', UserDeviceTokenController::class)->only(['store']);
});
