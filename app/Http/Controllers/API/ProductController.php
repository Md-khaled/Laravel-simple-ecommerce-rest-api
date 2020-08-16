<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use DB;
use Image;
use Carbon\Carbon;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::orderBy('id','desc')->paginate(3);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();
         $request->validate([
           'product_name'=>'required',
           'category_id'=>'required',
           'product_short_description'=>'required',
           'product_long_description'=>'required',
           //'product_image'=>'bail|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           'product_price'=>'required|integer',
           'publication_status'=>'required',
        ]);

        $get_last_product_insert_id =  Product::insertGetId([
           'product_name'=>$request->product_name,
           'category_id'=>$request->category_id,
           'product_short_description'=>$request->product_short_description,
           'product_long_description'=>$request->product_long_description,
           'product_price'=>$request->product_price,
           'publication_status'=>$request->publication_status,
        ]);
        if ($request->product_image) {

            Product::where('id',$get_last_product_insert_id)->update([
                'product_image'=>$this->imageprocess($request->product_image),
            ]);
        }
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
        return Product::find($id);
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
        $request->validate([
            'product_name'=>'required',
            'category_id'=>'required',
            'product_short_description'=>'required',
            'product_long_description'=>'required',
            'product_price'=>'required|integer',
            'publication_status'=>'required',
         ]);

        Product::where('id',$id)->update([
            'product_name'=>$request->product_name,
            'category_id'=>$request->category_id,
            'product_short_description'=>$request->product_short_description,
            'product_long_description'=>$request->product_long_description,
            'product_price'=>$request->product_price,
            'publication_status'=>$request->publication_status,
        ]);

        if ($request->product_image) {

            $product =  Product::find($id);

            if($product->product_image =='default_img.jpg'){
                Product::where('id',$id)->update([
                    'product_image'=>$this->imageprocess($request->product_image),
                ]);

            }elseif ($product->product_image!=$request->product_image) {
                unlink(base_path('public/uploads/images/product/'.$product->product_image));
                Product::where('id',$id)->update([
                    'product_image'=>$this->imageprocess($request->product_image),
                ]);
            }


        }
         return response(['success'=>'Data updated successfully'],201);
    }
 public function imageprocess($image)
 {
    $exploed1 = explode(";", $image);
    $exploed2 = explode("/", $exploed1[0]);
    $filename =  time().'.'.$exploed2[1];

    Image::make($image)->resize(215, 215)->save(base_path('public/uploads/images/product/'.$filename),50);
    return $filename;
 }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product =  Product::find($id);
        if($product->product_image =='default_img.jpg'){
            Product::find($id)->delete();
        }else{
            Product::find($id)->delete();
            unlink(base_path('public/uploads/images/product/'.$product->product_image));
        }
        return response(['success'=>'Data deleted successfully'],201);
    }
}
