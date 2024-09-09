<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'phoneNumber',
        'name',
        'email',
        'city_id',
        'district_id',
        'address',
        'photoURL',
        'activated',
        'role',
        'referenceCode'
    ];

    protected $casts = [
        'activated' => 'boolean',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
