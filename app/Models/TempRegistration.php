<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempRegistration extends Model
{
    protected $fillable = [
        'email',
        'registration_data',
        'expires_at'
    ];

    protected $casts = [
        'registration_data' => 'array',
        'expires_at' => 'datetime'
    ];

    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }
}
