<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'company' => $this->name,
            'items' => $this->whenPivotLoaded('stock_products', function(){
                return $this->pivot->number_of_items;
            })
        ];
    }
}
