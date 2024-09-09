<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagePath extends Model
{
    use HasFactory;

    protected $fillable = ['image_url', 'image_name', 'cart_id'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
