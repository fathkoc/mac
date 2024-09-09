<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'otps';

    protected $fillable = ['phone_number', 'sms_code'];

    protected $casts = [
        'phone_number' => 'string',
        'sms_code' => 'string',
    ];
}
