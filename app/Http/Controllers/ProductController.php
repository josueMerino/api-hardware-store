<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\ProductoResourceCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StockProductResource;
use App\Http\Resources\UserResource;
use App\Product;
use App\StockProduct;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $product;
    protected $stockProduct;

    public function __construct(Product $product, StockProduct $stockProduct)
    {
        $this->product = $product;
        $this->stockProduct = $stockProduct;
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
            'image' => 'nullable|image|mimes:jpeg,bmp,jpg,png',
            'number_of_items' => 'required|numeric|min:1',
            'category' => 'required',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::all();
        return new ProductoResourceCollection($product);
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

            $data = [
                'id' => $request->id,
                'title' => $request->title,
                'price' => $request->price,
                'information' => $request->information,
                'image' => $request->image,
                'number_of_items' => $request->number_of_items,
                'category' => $request->category,
            ];


            $category = Category::create($data);

            $product = Product::create([
                'category_id' => $category->id,
            ] + $data);

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

                $product->save();
            }

            $dataStockProduct = [
                'product_id' => $product->id,
                'number_of_items' => $data['number_of_items'],
            ];

            $this->stockProduct->create($dataStockProduct);

            // We call the relationship
            $product->stockProduct;
            //dd($product->category());
            //$product->stockProduct->select('number_of_items')->where('product_id', $product->id)->get();
            $product->category;

            return new ProductResource($product);

            //return



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

            return new ProductResource($product);
        } catch (Exception $error)
        {
            return response()->json([
                'message' => 'Not Found',
                'error' => $error,
            ], 404);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Product $product, StockProduct $stockProduct, Category $category)
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

        $stockProduct->where('product_id', $product->id)->update(['number_of_items' => $request->number_of_items]);

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
    public function destroy(Product $product, StockProduct $stockProduct, Category $category)
    {
        try {

            if ($product->image_path)
            {
                Cloudder::delete($product->image_path);
            }

            $product->delete();

            $stockProduct->delete();

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
