<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Cabang extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'cabangs';

    protected $fillable = [
        'nama_cabang',
        'alamat',
        'jam_buka',
        'jam_tutup',
        'foto_cabang',
        'denah_cabang',
    ];
}