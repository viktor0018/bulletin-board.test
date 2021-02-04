<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiCode;
use Illuminate\Support\Facades\Validator;
use App\Models\Advert;
use App\Models\Moderation;

class ModerateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $adverts = Advert::where('advert_status_id',2)
            ->with('author')
            ->with('category')
            ->with('city')
            ->with('photo')
            ->with('status')
            ->orderBy("id", "desc")->get();

        return $this->respond($adverts);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'advert_id' =>"required|exists:adverts,id",
            'reason' =>"required|string|min:3|max:512",
        ]);


        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        Moderation::create(
            $request->all() +
            [
                'resolution' => 'rejected',
                'user_id' => $request->user()->id,
                'moderated_at' =>now()
            ]
        );

        $advert = Advert::findOrFail($request->input('advert_id'));

        $advert->advert_status_id =3;
        $advert->save();

        return $this->respond($advert);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'advert_id' =>"required|exists:adverts,id",
        ]);


        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        Moderation::create(
            $request->all() +
            [
                'resolution' => 'approved',
                'user_id' => $request->user()->id,
                'moderated_at' =>now()
            ]
        );

        $advert = Advert::findOrFail($request->input('advert_id'));

        $advert->advert_status_id = 5 ;
        $advert->save();

        return $this->respond($advert);
    }
}
