<?php

namespace App\Http\Controllers;

use App\Card;
use App\Http\Requests\CardRequest;
use App\Http\Resources\CardResource;
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

        $data = $this->card->create( [
            'id' => $request->id,
            'user_id' => $auth,
            'card_number' => $request->card_number,
            ] );

        return response()->json(new CardResource($data), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        return new CardResource($card);
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
