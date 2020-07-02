<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =
    ['title', 'price', 'information', 'image', 'image_path', 'category_id', 'number_of_items', 'company_id'];


    // Relantionships, attributes and mutators
    public function cartUserProducts()
    {
        return $this->belongsToMany(CartUserProduct::class)->withTimestamps();
    }

    public function wishlists()
    {
        return $this->belongsToMany(Wishlist::class)->withTimestamps();
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'stock_products')->withTimestamps()->withPivot('number_of_items') ;
    }
}
