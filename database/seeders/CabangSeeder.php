<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        Cabang::create([
            'nama_cabang' => 'ReservaSeat Bogor',
            'alamat' => 'Bogor',
            'jam_buka' => '10:00',
            'jam_tutup' => '22:00',
            'foto_cabang' => null,
            'denah_cabang' => null,
        ]);

        Cabang::create([
            'nama_cabang' => 'ReservaSeat Bandung',
            'alamat' => 'Bandung',
            'jam_buka' => '10:00',
            'jam_tutup' => '22:00',
            'foto_cabang' => null,
            'denah_cabang' => null,
        ]);
    }
}