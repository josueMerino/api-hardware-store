<?php

namespace App\Http\Controllers;

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
            'image' => 'nullable|image',
            'number_of_items' => 'required|numeric|min:1',
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
            ];

            $product = Product::create($data);
            if ($request->file('image') && $request->image)
            {
                $image = $request->file('image');

                $name = $request->file('image')->getClientOriginalName();

                $imageName = $request->file('image')->getRealPath();

                Cloudder::upload($imageName, null);

                list($width, $height) = getimagesize($imageName);

                $imageURL = Cloudder::show(Cloudder::getPublicId(), [
                    "width" => $width,
                    "height" => $height,
                ]);

                $image->move(storage_path("app/public/uploads/productImages"), $name);


                $product->image = $imageURL;

                $product->save();
            }

            $dataStockProduct = [
                'product_id' => $product->id,
                'number_of_items' => $data['number_of_items'],
            ];

            $this->stockProduct->create($dataStockProduct);

            // We call the relationship
            $product->stockProduct;

            //$product->stockProduct->select('number_of_items')->where('product_id', $product->id)->get();

            //dd($product);
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
    public function update(Request $request, Product $product, StockProduct $stockProduct)
    {

        //dd($request);

        $validator = Validator::make($request->all(), [
            'title' => 'max:120',
            'image' => 'image|nullable',
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

        $data = [
            'id' => $request->id,
            'title' => $request->title,
            'price' => $request->price,
            'information' => $request->information,
            'image' => $request->image,
            'number_of_items' => $request->number_of_items,
        ];
        $product->update($data);

        if ($request->file('image') && $request->image)
        {
            Storage::disk('public')->delete($product->image);
            $product->image = $request->file('image')->store('productsImages', 'public');
            $product->image = storage_path($product->image);
            $product->save();
        }

        $dataStockProduct = [
            'product_id' => $product->id,
            'number_of_items' => $data['number_of_items'],
        ];

        $stockProduct->update($dataStockProduct);

        $product->stockProduct;

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, StockProduct $stockProduct)
    {
        try {
            $product->delete();

            $stockProduct->delete();

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
