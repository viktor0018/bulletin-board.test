<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdvertController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ModerateController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminRegionController;
use App\Http\Controllers\AdminCityController;
use App\Http\Controllers\AdminCategoryController;

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

Route::middleware('auth:sanctum')->post('/advert/moderate', [AdvertController::class, 'moderate']);

Route::post('/photo/store', [PhotoController::class, 'store']);
Route::get('/photos', [PhotoController::class, 'index']);

Route::middleware('auth:sanctum')->post('/photo/delete', [PhotoController::class, 'destroy']);
Route::middleware('auth:sanctum')->post('/photo/update', [PhotoController::class, 'update']);

Route::middleware('auth:sanctum')->get('/moderate/index', [ModerateController::class, 'index']);

Route::middleware('auth:sanctum')->post('/moderate/reject', [ModerateController::class, 'reject']);

Route::middleware('auth:sanctum')->post('/moderate/activate', [ModerateController::class, 'activate']);



Route::middleware('auth:sanctum')->get('/admin/user/index', [AdminUserController::class, 'index']);
Route::middleware('auth:sanctum')->post('/admin/user/destroy', [AdminUserController::class, 'destroy']);


Route::middleware('auth:sanctum')->post('/admin/user/update', [AdminUserController::class, 'update']);


Route::middleware('auth:sanctum')->get('/admin/user/show', [AdminUserController::class, 'show']);

Route::middleware('auth:sanctum')->get('/admin/user/list', [AdminUserController::class, 'userlist']);


Route::middleware('auth:sanctum')->post('/admin/user/create', [AdminUserController::class, 'store']);



Route::middleware('auth:sanctum')->get('/admin/region/index', [AdminRegionController::class, 'index']);
Route::middleware('auth:sanctum')->post('/admin/region/destroy', [AdminRegionController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/admin/region/show', [AdminRegionController::class, 'show']);
Route::middleware('auth:sanctum')->post('/admin/region/update', [AdminRegionController::class, 'update']);

Route::middleware('auth:sanctum')->post('/admin/region/create', [AdminRegionController::class, 'store']);



Route::get('/admin/city/index', [AdminCityController::class, 'index']);
Route::middleware('auth:sanctum')->get('/admin/city/show', [AdminCityController::class, 'show']);
Route::middleware('auth:sanctum')->post('/admin/city/destroy', [AdminCityController::class, 'destroy']);
Route::middleware('auth:sanctum')->post('/admin/city/update', [AdminCityController::class, 'update']);
Route::middleware('auth:sanctum')->post('/admin/city/create', [AdminCityController::class, 'store']);


Route::get('/admin/category/index', [AdminCategoryController::class, 'index']);
Route::middleware('auth:sanctum')->get('/admin/category/show', [AdminCategoryController::class, 'show']);
Route::middleware('auth:sanctum')->post('/admin/category/destroy', [AdminCategoryController::class, 'destroy']);
Route::middleware('auth:sanctum')->post('/admin/category/update', [AdminCategoryController::class, 'update']);
