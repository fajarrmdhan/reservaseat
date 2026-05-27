<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PersonalAccessToken extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'personal_access_tokens';

    protected $fillable = [
        'name',
        'token',
        'abilities',
        'tokenable_id',
        'tokenable_type',
        'expires_at',
    ];

    protected $casts = [
        'abilities' => 'array',
    ];
}