<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Meja extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'mejas';

    protected $fillable = [
        'cabang_id',
        'nomor_meja',
        'kapasitas',
        'status',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}