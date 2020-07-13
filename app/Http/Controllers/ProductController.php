<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Product;
use Exception;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * This function declares the rules that our data must have
     */
    public function rules()
    {
        return [
            'title' => 'required|max:120',
            'price' => 'required|numeric',
            'information' => 'required|max:255',
            'image' => 'url|image|mimes:jpeg,bmp,jpg,png',
            'companies' => 'required|string',
            'category_id' => 'required|numeric|min:1',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::with(['category', 'companies'])->get();

        return ProductResource::collection($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            
            $validator = Validator::make($request->all(), $this->rules());

            if ($validator->fails())
            {
                return response()->json([
                    'message' => 'The given data is invalid',
                    'errors'=>$validator->errors()
                ],422);
            }

            $dataProduct = [
                'id' => $request->id,
                'title' => $request->title,
                'price' => $request->price,
                'information' => $request->information,
            ];


            $product = Product::create($dataProduct);

            dd($product);
            if ($request->file('image') && $request->image)
            {

                $imageName = $request->file('image')->getRealPath();

                Cloudder::upload($imageName, null);

                list($width, $height) = getimagesize($imageName);

                $imageURL = Cloudder::secureShow(Cloudder::getPublicId(), [
                    "width" => $width,
                    "height" => $height,
                ]);

                $product->image = $imageURL;

                $product->image_path = Cloudder::getPublicId();


            }

            $product->category()->associate($request->category_id);


            foreach (json_decode($request->companies) as $company) {
                //dd($company);
                $product->companies()->attach($company->company_id,[
                    'number_of_items' => $company->number_of_items,
                ]);
            }


            $product->save();

            $product->category;
            $product->companies;
            return new ProductResource($product);

        }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        try {
            $product->stockProduct;
            $product->category;
            return new ProductResource($product);
        } catch (Exception $error)
        {
            return response()->json([
                'message' => 'Not Found',
                'error' => $error,
            ], 500);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Product $product)
    {

        //dd($request->getContent());

        $validator = Validator::make($request->all(), [

            'title' => 'max:120',
            'image' => 'image|nullable|mimes:jpeg,bmp,jpg,png',
            'price' => 'numeric',
            'information' => 'max:255',
            'number_of_items' => 'numeric|min:1',

        ]);

        if ($validator->fails())
        {
            return response()->json([
                'message' => 'The given data is invalid',
                'errors'=>$validator->errors()
            ],422);
        }



        $product->update($request->all());

        if ($request->file('image') && $request->image)
        {

            Cloudder::delete($product->image_path);

            $imageName = $request->file('image')->getRealPath();

            Cloudder::upload($imageName, null);

            list($width, $height) = getimagesize($imageName);

            $imageURL = Cloudder::secureShow(Cloudder::getPublicId(), [
                "width" => $width,
                "height" => $height,
            ]);

            $product->image = $imageURL;
            $product->image_path = Cloudder::getPublicId();
            $product->save();
        }



        $product->stockProduct;
        $product->category;
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        try {

            if ($product->image_path)
            {
                Cloudder::delete($product->image_path);
            }

            $product->delete();

            $category->delete();

            return response()->json([
                'message' => 'Eliminado con éxito'], 200);
        }
        catch (Exception $error)
        {
            return response()->json([
                'message' => 'Error',
                'error' => $error,
        ], 500);
        }

    }
}
