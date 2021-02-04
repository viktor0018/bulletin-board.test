<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\UserRole;
use Illuminate\Support\Facades\Validator;
use App\ApiCode;


class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $users = User::orderBy("id", "desc")
            ->with('status')
            ->with('role')
            ->get();

        return $this->respond($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name' =>"required|string|min:3|max:128",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'middlename' => $request->middlename,
            'email' => $request->email,
            'password' => $request->password,
            'user_role_id' => $request->user_role_id,
            'phone' => $request->phone,
            'user_status_id' =>  $request->user_status_id
        ]);


        return $this->respond($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'id' =>"required|exists:users,id",
        ]);


        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $advert =  User::findOrFail($request->input('id'));

        return $this->respond($advert);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'id' =>"required|exists:users,id",
            'name' =>"required|string|min:3|max:128",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $user = User::findOrFail($request->input('id'));


        $user->fill($request->all())->save();

        return $this->respond($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'id' =>"required|exists:users,id",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $user = User::findOrFail($request->input('id'));

        $user->delete();

        return $this->respond($user);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userlist()
    {
        $resp = [
            'UserRoles' => UserRole::all(),
            'UserStatus' => UserStatus::all(),
        ];
        return $this->respond($resp);
    }

}
