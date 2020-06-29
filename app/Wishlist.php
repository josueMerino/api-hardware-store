<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{

    // Mass Asignments
    protected $fillable = [
        'user_id', 'name', 'product_id'
    ];

    // Relantionships, attributes and mutators
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_wishlist', 'product_id', 'wishlist_id')
                ->withTimestamps();
    }


}
