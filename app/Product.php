<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =
    ['title', 'price', 'information', 'image', 'image_path', 'category_id'];


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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
