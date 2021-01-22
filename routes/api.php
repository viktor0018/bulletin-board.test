<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/forgot-password', [AuthController::class, 'forgot_password']);
Route::post('/reset-password', [AuthController::class, 'reset_password']);
//Route::middleware('auth:sanctum')->post('/verify-email', [AuthController::class, 'verify_email']);
Route::middleware('auth:sanctum')->get('/verify-email/{id}/{hash}', [AuthController::class, 'verify_email'])->name('verification.verify');;
