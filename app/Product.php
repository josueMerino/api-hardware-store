<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =
    ['name', 'price', 'information',];


    // Relantionships, attributes and mutators
    public function cartUserProducts()
    {
        return $this->belongsToMany(CartUserProduct::class);
    }

    public function wishlists()
    {
        return $this->belongsToMany(Wishlist::class);
    }

    public function stockProducts()
    {
        return $this->hasOne(StockProduct::class);
    }
}
