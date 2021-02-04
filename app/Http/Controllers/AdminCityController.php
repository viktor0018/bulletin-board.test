<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ApiCode;
use App\Models\Region;
use App\Models\City;

class AdminCityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->id){
            $cities = City::orderBy("id", "desc")
                ->with('region')
                ->get();
            return $this->respond($cities);
        }
        else
        {
            $validator = Validator::make($request->all(),
            [
                'id' =>"required|exists:regions,id",
            ]);

            if ($validator->fails()) {
                    return $this->respondError($validator->errors(),
                    ApiCode::VALIDATION_ERROR,"Validation error");
            }

            $cities = City::where('region_id',$request->id)
                ->orderBy("id", "desc")
                ->with('region')
                ->get();

            return $this->respond($cities);
        }
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
            'region_id' =>"required|exists:regions,id",
            'name' =>"required|string|min:3|max:128",
            'slug' =>"required|string|min:3|max:128",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $city = City::create($request->all());

        return $this->respond($city);
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
            'id' =>"required|exists:cities,id",
        ]);


        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

         $advert = City::with('region')->get()->find($request->input('id'));

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
            'id' =>"required|exists:cities,id",
            'region_id' =>"required|exists:regions,id",
            'name' =>"required|string|min:3|max:128",
            'slug' =>"required|string|min:3|max:128",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $city = City::findOrFail($request->input('id'));


        $city->fill($request->all())->save();

        return $this->respond($city);
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
            'id' =>"required|exists:cities,id",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $city = City::findOrFail($request->input('id'));

        $city->delete();

        return $this->respond($city);
    }
}
