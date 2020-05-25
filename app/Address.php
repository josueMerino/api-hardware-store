<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function address()
    {
        return $this->belongsTo(User::class);
    }
}
