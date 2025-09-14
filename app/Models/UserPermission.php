<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'route_name',
        'menu_title',
        'is_allowed'
    ];

    protected $casts = [
        'is_allowed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
