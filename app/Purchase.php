<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function payMethod()
    {
        return $this->belongsTo(PayMethod::class);
    }
}
