<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'plateCode'];

    public function districts()
    {
        return $this->hasMany(District::class, 'city_id');
    }
}
