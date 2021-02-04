<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ApiCode;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $categories = Category::orderBy("id", "asc")
            ->with('parent')
            ->get();

        foreach ($categories as $category) {
            if($category->parent_id == $category->id){
             $category->level = 1;
            }else{
                //echo '<'.$categories[$category->parent_id-1]->id.'>';
                $category->level  = $categories[$category->parent_id-1]->level +1;
            }
            //echo $category->id.' '.$category->parent_id.' '.$category->level.'|||';
        }

        return $this->respond($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
            'id' =>"required|exists:categories,id",
        ]);


        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $category =  Category::findOrFail($request->input('id'));

        return $this->respond($category);
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
            'id' =>"required|exists:categories,id",
            'parent_id' =>"required|exists:categories,id",
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

        $category = Category::findOrFail($request->input('id'));


        $category->fill($request->all())->save();

        return $this->respond($category);
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
            'id' =>"required|exists:categories,id",
        ]);

        if ($validator->fails()) {
                return $this->respondError($validator->errors(),
                ApiCode::VALIDATION_ERROR,"Validation error");
        }

        if( $request->user()->user_role_id != 4){
            return $this->respondError([],ApiCode::ACCESS_DENIDED,"Access denied");
        }

        $category = Category::findOrFail($request->input('id'));

        $category->delete();

        return $this->respond($category);
    }
}
