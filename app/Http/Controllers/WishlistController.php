<?php

namespace App\Http\Controllers;

use App\Http\Resources\WishlistResource;
use App\Product;
use App\User;
use App\Wishlist;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    protected $wishlist;

    public function __construct(Wishlist $wishlist)
    {
        $this->wishlist = $wishlist;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:100|unique:wishlists',
            'product_id' => 'required',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            //dd($request->all());
            $auth = auth()->user();

            $validator = Validator::make($request->all(), $this->rules());

            if ($validator->fails())
            {
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }

            $wishlist = $this->wishlist->create([
                    'user_id' => $auth->id,
                    'name' =>$request->name,
                ]);


            //dd($wishlist->id);
            $wishlist->products()->attach($request->product_id);

            // Hacer un query que seleccione los datos que queremos
            $wishlist->products;
            return new WishlistResource($wishlist);
        }
        catch (Exception $error)
        {
            return response()->json([
                'message' => $error,
                'status_code' => 500,
            ], 500);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function show(Wishlist $wishlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wishlist $wishlist)
    {
        //
    }
}
