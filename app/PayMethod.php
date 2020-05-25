<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayMethod extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }


    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
