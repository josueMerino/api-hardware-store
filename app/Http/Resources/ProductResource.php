<?php

namespace App\Http\Resources;

use App\Product;
use App\StockProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'price'=>$this->price,
            'information'=>$this->information,
            'image'=>$this->image,
            'number_of_items' => new StockProductResource($this->stockProduct),
        ];
    }
}
