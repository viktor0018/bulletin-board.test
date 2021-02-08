<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\ApiCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Jobs\SendPasswordResetJob;
use App\Jobs\SendEmailVerificationNotificationJob;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function login(Request $request){

        $validator = Validator::make($request->all(),
        [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {

            return $this->respondError(['email' => ['The provided credentials are incorrect.']],
            ApiCode::INVALID_CREDENTIALS,"Invalid credentials");
        }

        $token =  $user->createToken('token-name', ['server:update'])->plainTextToken;

        return $this->respond([
            'access_token' => $token,
            'access_type' => 'bearer',
            'user_name' =>$user->name . ' '. $user->surname,
        ], "User have been successfully logged in");
    }

    public function register(Request $request)
    {

      $response = (new Client)
         ->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
               'form_params' => [
                  'secret' => env('APP_RECAPTCHA', ''),
                  'response' => $request->recaptcha_token
               ]
            ]
         );

        $captcha =  $response->getBody();
        $captcha = json_decode($captcha, true);
        if ($captcha["success"] != 1) {
            return $this->respondError([],
                ApiCode::RECAPTCHA_ERROR,"reCaptcha error");
        }

        $validator = Validator::make($request->all(),
        [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:255|unique:users',
        ]);

        if ($validator->fails()) {
            return $this->respondError($validator->errors(),
            ApiCode::VALIDATION_ERROR,"Validation error");
        }

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

        $user->sendEmailVerificationNotification();

        //dispatch(new SendEmailVerificationNotificationJob($details));

        $token =  $user->createToken('token-name', ['server:update'])->plainTextToken;

        return $this->respond([
            'access_token' => $token,
            'access_type' => 'bearer',
            'user_name' =>$user->name . ' '. $user->surname,
        ], "User have been successfully registered and logged in");

    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this->respondWithMessage('User successfully logged out');
    }


    public function forgot_password(Request $request){

        $validator = Validator::make($request->all(),
        [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->respondError($validator->errors(),
            ApiCode::VALIDATION_ERROR,"Validation error");
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        /*$status = Password::sendResetLink(
            $request->only('email'),
            function($message) {
                $message->subject('Registration Email');
            }
        );*/

        $token =  Password::createToken(User::where('email',$request->input('email'))->first());

        $details['email'] = $request->only('email');
        $details['link'] = "http://localhost:8080/#/password_reset?token=$token&email=".$request->input('email');
        dispatch(new SendPasswordResetJob($details));


        return $this->respondWithMessage('Reset password link sent on your email id.');
    }



    public function reset_password(Request $request)
    {

        $validator = Validator::make($request->all(),
        [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return $this->respondError($validator->errors(),
            ApiCode::VALIDATION_ERROR,"Validation error");
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.

        if($status == Password::PASSWORD_RESET ){
            return $this->respondWithMessage('Password has been successfully changed');
        }else{
            return $this->respondBadRequest(ApiCode::INVALID_RESET_PASSWORD_TOKEN);
        }

    }

    /*
    public function verify_email(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->respondBadRequest(ApiCode::EMAIL_ALREADY_VERIFIED);
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->respondWithMessage('An email has been sent.');
    }
    */


    public function verify_email(EmailVerificationRequest $request){

        if ($request->user()->hasVerifiedEmail()) {
            return $this->respondBadRequest(ApiCode::EMAIL_ALREADY_VERIFIED);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->respondWithMessage('An email has been verified.');

    }
}
