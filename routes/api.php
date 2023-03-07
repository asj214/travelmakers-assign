<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function(){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

/*
Route::group(['middleware' => ['auth:sanctum']], function ($router) {
    Route::get('hotels', [HotelController::class, 'index'])->withoutMiddleware('auth:sanctum');
    Route::post('hotels', [HotelController::class, 'store']);
    Route::get('hotels/{id}', [HotelController::class, 'show'])->withoutMiddleware('auth:sanctum');
    Route::put('hotels/{id}', [HotelController::class, 'update']);
    Route::delete('hotels/{id}', [HotelController::class, 'destroy']);

    // Route::apiResource('hotels', HotelController::class);
});
*/

Route::apiResource('hotels', HotelController::class);