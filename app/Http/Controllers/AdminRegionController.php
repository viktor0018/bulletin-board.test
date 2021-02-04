<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ApiCode;
use App\Models\Region;

class AdminRegionController extends Controller
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

        $users = Region::orderBy("id", "desc")
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

        $region = Region::create([
            'name' => $request->name,
        ]);

        return $this->respond($region);
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
            'id' =>"required|exists:regions,id",
        ]);


        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $advert =  Region::findOrFail($request->input('id'));

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
            'id' =>"required|exists:regions,id",
            'name' =>"required|string|min:3|max:128",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $region = Region::findOrFail($request->input('id'));


        $region->fill($request->all())->save();

        return $this->respond($region);
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
            'id' =>"required|exists:regions,id",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $user = Region::findOrFail($request->input('id'));

        $user->delete();

        return $this->respond($user);
    }

}
