<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


Route::post('/register', function (Request $request) {
/*
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|confirmed|min:8',
    ]);*/

    Auth::login($user = User::create([
        'name' => $request->name,
        'surname' => $request->surname,
        'middlename' => $request->middlename,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'user_role_id' => 1,
        'phone' => $request->phone,
        'user_status_id' => 1
    ]));

    event(new Registered($user));



     return $user->createToken('token-name', ['server:update'])->plainTextToken;
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


use Illuminate\Validation\ValidationException;

Route::post('/login', function (Request $request) {
    /*$request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);*/

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken('token-name', ['server:update'])->plainTextToken;
});