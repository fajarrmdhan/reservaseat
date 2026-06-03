<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Reservasi extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'reservasis';

    protected $fillable = [

        'user_id',

        'cabang_id',

        'meja_id',

        'kode_reservasi',

        'tanggal_booking',

        'jam_mulai',

        'durasi',

        'blocked_slots',

        'catatan',

        'source',

        'status',
    ];

    protected $casts = [

        'blocked_slots' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }
}
