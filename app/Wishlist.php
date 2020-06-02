<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{

    // Mass Asignments
    protected $fillable = [
        'user_id', 'number_of_items'
    ];

    // Relantionships, attributes and mutators
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    protected $attributes = [
        'number_of_items' => 1,
    ];
}
