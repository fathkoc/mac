<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'postCode', 'city_id'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
