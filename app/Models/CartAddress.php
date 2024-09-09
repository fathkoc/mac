<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 'low_description', 'city_id', 'district_id', 'cart_id'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
