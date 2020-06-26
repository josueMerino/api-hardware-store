<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductoResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'data' => $this->collection([
                'id' => $this->id,
                'title' => $this->title,
                'price' => $this->price,
                'image' => $this->image,
                'items' => $this->whenLoaded('stockProduct')->number_of_items,
                'category' => $this->whenLoaded('category')->category,
            ]),
        ];
    }
}
