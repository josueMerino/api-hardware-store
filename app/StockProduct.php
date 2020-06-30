<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockProduct extends Model
{

    protected $fillable = [
        'product_id', 'number_of_items',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected $attributes = [
        'number_of_items' => 1,
    ];
}
