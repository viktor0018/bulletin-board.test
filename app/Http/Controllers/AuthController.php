<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request){
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
    }

    public function register(Request $request){
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
        return [
            'access_token' =>$user->createToken('token-name', ['server:update'])->plainTextToken,
            'user' => $user] ;
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }

}
