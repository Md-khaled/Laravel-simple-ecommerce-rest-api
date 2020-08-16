<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use Carbon\Carbon;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::orderBy('id','desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // return $request->all();
        $validate = $request->validate([
            'category_name'=>'required|unique:categories,category_name',
            'category_description'=>'required',
            'publication_status'=>'required',
        ]);
        Category::create([
            'category_name'=>$request->category_name,
            'category_description'=>$request->category_description,
            'publication_status'=>$request->publication_status,
        ]);
        return response(['success'=>'Data inserted successfully'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Category::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $validate = $request->validate([
            'category_name'=>'required|unique:categories,category_name,'.$id,
            'category_description'=>'required',
            'publication_status'=>'required',
        ]);
        Category::where('id',$id)->update([
            'category_name'=>$request->category_name,
            'category_description'=>$request->category_description,
            'publication_status'=>$request->publication_status,
        ]);
        return response(['success'=>'Data updated successfully'],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response(['success'=>'Data deleted successfully'],201);
    }
}
