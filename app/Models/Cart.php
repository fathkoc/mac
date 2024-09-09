<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'position', 'title', 'description', 'phone',
        'map_lat', 'map_long', 'discount', 'menu',
        'brand_like', 'like'
    ];

    public function dates()
    {
        return $this->hasMany(CartDate::class);
    }

    public function address()
    {
        return $this->hasOne(CartAddress::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'cart_category')->using(CartCategory::class);
    }

    public function imagePaths()
    {
        return $this->hasMany(ImagePath::class);
    }
}
