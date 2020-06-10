<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoResourceCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            //'image' => 'nullable|image',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ProductoResourceCollection($this->product->get());
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
            ],400);
        }
        $product = $this->product->create($request->all());
        if ($request->image)
        {
            $product->image = $request->file('image')->store('productsImages', 'public');
            $product->save();
        }

        return (new ProductResource($product))
        ->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
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
        $product->update($request->all());

        if ($product->image)
        {
            Storage::disk('public')->delete($product->image);
            $product->image = $request->file('image')->store('productosImages', 'public');
            $product->save();
        }

        return new UserResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json('Eliminado con éxito', 204);
    }
}
