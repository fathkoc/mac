<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomToken extends Model
{
    use HasFactory;

    protected $table = 'custom_tokens'; 

    protected $fillable = [
        'account_id', 
        'name', 
        'token', 
        'abilities', 
        'last_used_at'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
