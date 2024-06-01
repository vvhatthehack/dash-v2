<?php

// app/Models/SessionHistory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'last_activity',
        'is_active',
        'country ',
        'city',
        'region',
        'timezone',
    ];
}
