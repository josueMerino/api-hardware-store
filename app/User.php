<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'birth_date', 'password', 'remember_token', 'country', 'is_admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Default value: is_admin = false;
    protected $attributes = [
        'is_admin' => false,
    ];

    // Relantionships, attributes and mutators
    public function cartUserProduct()
    {
        return $this->hasOne(CartUserProduct::class);
    }

    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function payMethods()
    {
        return $this->belongsToMany(PayMethod::class);
    }
}
