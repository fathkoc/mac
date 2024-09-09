<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_category')->using(CartCategory::class);
    }
}
