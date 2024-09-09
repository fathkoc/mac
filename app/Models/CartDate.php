<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDate extends Model
{
    use HasFactory;

    protected $fillable = ['day', 'start_hour', 'end_hour', 'cart_id'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
