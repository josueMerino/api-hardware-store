<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =
    ['title', 'price', 'information', 'image',];


    // Relantionships, attributes and mutators
    public function cartUserProducts()
    {
        return $this->belongsToMany(CartUserProduct::class)->withTimestamps();
    }

    public function wishlists()
    {
        return $this->belongsToMany(Wishlist::class)->withTimestamps();
    }

    public function stockProduct()
    {
        return $this->hasOne(StockProduct::class);
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)->withTimestamps();
    }
}
