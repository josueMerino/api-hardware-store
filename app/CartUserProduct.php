<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartUserProduct extends Model
{

    // Relantionships, attributes and mutators
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
