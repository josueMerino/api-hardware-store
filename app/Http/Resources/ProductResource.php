<?php

namespace App\Http\Resources;

use App\Product;
use App\StockProduct;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

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
        $response = [
            'id'=>$this->id,
            'title'=>$this->title,
            'price'=>$this->price,
            'information'=>$this->information,
            'image'=>$this->image,
            //'image_path' => $this->image_path,
            'companies' => CompanyResource::collection($this->whenLoaded('companies')),
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];

        return $response;
    }
}
