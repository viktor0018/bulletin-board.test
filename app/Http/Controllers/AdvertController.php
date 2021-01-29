<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\Category;
use App\Models\City;
use App\Models\Region;
use Illuminate\Http\Request;
use App\ApiCode;
use Illuminate\Support\Facades\Validator;

class AdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $validator = Validator::make($request->all(),
        [
            'category_id' =>"required|exists_or_null:categories,id",
            'city_id' => "required|exists_or_null:cities,id",
            'price_from' => 'required|nullable|integer|lte:price_to|min:0',
            'price_to' => 'required|nullable|integer|gte:price_from|min:0',
            'search_text' => 'string|min:3|max:36|nullable',
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        $category_id = $request->input('category_id');
        $city_id = $request->input('city_id');
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');
        $search_text = $request->input('search_text');

        $adverts = Advert
            ::where(function($query) use ($category_id)  {
                if($category_id > 0) {
                    $query->where('category_id', $category_id);
                }
            })
            ->where(function($query) use ($city_id)  {
                if($city_id > 0) {
                    $query->where('city_id', $city_id);
                }
            })
            ->where(function($query) use ($price_from)  {
                if($price_from > 0) {
                    $query->where('price', '>' ,$price_from);
                }
            })
            ->where(function($query) use ($price_to)  {
                if($price_to > 0) {
                    $query->where('price', '<' ,$price_to);
                }
            })
            ->where(function($query) use ($search_text)  {
                if($search_text != '') {
                    $query->where('title', 'like' , '%'.$search_text.'%')
                    ->orWhere('description', 'like' , '%'.$search_text.'%');
                }
            })
            ->with('author')
            ->with('category')
            ->with('city')
            ->with('photo')
            ->with('status')
            ->orderBy("id", "desc")
            ->paginate(16);

        return $this->respond($adverts);
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
            'title' =>"required|string|min:3|max:128",
            'description' =>"required|string|min:3|max:512",
            'city_id' =>"required|exists_or_null:cities,id",
            'category_id' =>"required|exists_or_null:categories,id",
        ]);


        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        $advert = Advert::create(
            $request->all() +
            ['user_id' => $request->user()->id,
            'advert_status_id' => 1,
            'published_at' =>now()
            ]
        );

        return $this->respond($advert);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'id' =>"required|exists:adverts,id",
        ]);


        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        $advert =  Advert::with('category')
        ->with('city')
        ->with('photo')
        ->with('author')
        ->with('status')->findOrFail($request->input('id'));

        return $this->respond($advert);
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
            'id' =>"required|exists:adverts,id",
            'title' =>"required|string|min:3|max:128",
            'description' =>"required|string|min:3|max:512",
            'city_id' =>"required|exists_or_null:cities,id",
            'category_id' =>"required|exists_or_null:categories,id",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        $advert = Advert::findOrFail($request->input('id'));


        if( $advert->user_id != $request->user()->id){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $advert->fill($request->all())->save();

        return $this->respond($advert);
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
            'id' =>"required|exists:adverts,id",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        $advert = Advert::findOrFail($request->input('id'));


        if( $advert->user_id != $request->user()->id){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $advert->delete();

        return $this->respond($advert);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $resp = [
            'categories' => Category::all(),
            'cities' => City::all(),
            'regions' => Region::all()
        ];
        return $this->respond($resp);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myadverts(Request $request)
    {
        $adverts = Advert::where('user_id',$request->user()->id)
            ->with('author')
            ->with('category')
            ->with('city')
            ->with('photo')
            ->with('status')
            ->orderBy("id", "desc")->get();

        return $this->respond($adverts);
    }
}
