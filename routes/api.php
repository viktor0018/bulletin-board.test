<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdvertController;

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
Route::middleware('auth:sanctum')->get('/verify-email/{id}/{hash}', [AuthController::class, 'verify_email'])->name('verification.verify');



Route::get('/adverts', [AdvertController::class, 'index']);
Route::get('/advert/show', [AdvertController::class, 'show']);
Route::middleware('auth:sanctum')->post('/advert/store', [AdvertController::class, 'store']);
Route::middleware('auth:sanctum')->post('/advert/update', [AdvertController::class, 'update']);
Route::middleware('auth:sanctum')->post('/advert/destroy', [AdvertController::class, 'destroy']);
Route::get('/advert/list', [AdvertController::class, 'list']);
Route::middleware('auth:sanctum')->get('/myadverts', [AdvertController::class, 'myadverts']);
