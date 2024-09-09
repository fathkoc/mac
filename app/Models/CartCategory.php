<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CartCategory extends Pivot
{
    protected $table = 'cart_category';

    protected $fillable = ['cart_id', 'category_id'];
}
