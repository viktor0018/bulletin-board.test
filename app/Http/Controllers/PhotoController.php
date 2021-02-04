<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Validator;
use App\ApiCode;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'advert_id'=>"required|exists:adverts,id",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        $photo = Photo::where('advert_id',$request->input('advert_id'))->get();
        return $this->respond($photo);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'picture' => 'required|mimes:jpeg,bmp,png|max:5120',
            'advert_id'=>"required|exists:adverts,id",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        $extension = $request->file('picture')->extension();
        if (!in_array($extension, ['jpg','jpeg','png']) ) {
            return $this->respondError($validator->errors(),
            ApiCode::VALIDATION_ERROR,"Only image file allowed");
        }

        $count = Photo::where('advert_id',$request->input('advert_id'))->count();

        if($count > 10){
            return $this->respondError($validator->errors(),
            ApiCode::VALIDATION_ERROR,"Image count must be less than 10");
        }

        $prev_photo = Photo::where('advert_id',$request->input('advert_id'))
        ->where('is_main',1)->first();

        $link =  $request->file('picture')->store('images');

        $photo = Photo::create([
            'advert_id'=>$request->input('advert_id'),
            'link' =>$link,
            'is_main'=>$prev_photo?0:1
        ]);
        return $this->respond($photo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'id' =>"required|exists:photos,id",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        $photo = Photo::findOrFail($request->input('id'));

        $prev_photo = Photo::where('advert_id',$photo->advert_id)
        ->where('is_main',1)->first();
        $prev_photo->is_main = 0;
        $prev_photo->save();

        $photo->is_main = 1;
        $photo->save();

        return $this->respond($photo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'id' =>"required|exists:photos,id",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        $photo = Photo::findOrFail($request->input('id'));


        if( $photo->advert->user_id != $request->user()->id){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        if($photo->is_main){
            $prev_photo = Photo::where('advert_id',$photo->advert_id)
            ->where('is_main',0)->first();
            if($prev_photo){
                $prev_photo->is_main = 1;
                $prev_photo->save();
            }
        }

        Storage::delete($photo->link);
        $photo->delete();

        return $this->respond($photo);
    }
}
