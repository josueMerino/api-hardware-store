<?php

namespace App\Http\Controllers;

use App\Card;
use App\Http\Requests\CardRequest;
use Illuminate\Http\Request;

class CardController extends Controller
{
    protected $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CardRequest $request)
    {


        $auth = auth()->user()->id;

        $data = $this->card->create( ['user_id' => $auth,] + $request->all() );

        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        $card->delete();

        return response()->json(null, 204);
    }
}
