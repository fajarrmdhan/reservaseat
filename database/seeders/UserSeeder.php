<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cabang;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $bogor = Cabang::where(
            'nama_cabang',
            'ReservaSeat Bogor'
        )->first();

        $bandung = Cabang::where(
            'nama_cabang',
            'ReservaSeat Bandung'
        )->first();

        User::create([
            'name' => 'Admin Master',
            'email' => 'master@reservaseat.com',
            'phone' => '081111111111',
            'password' => bcrypt('password123'),
            'photo_profile' => null,
            'role' => 'admin_master',
            'cabang_id' => null,
        ]);

        User::create([
            'name' => 'Admin Cabang Bogor',
            'email' => 'bogor@reservaseat.com',
            'phone' => '082222222222',
            'password' => bcrypt('password123'),
            'photo_profile' => null,
            'role' => 'admin_cabang',
            'cabang_id' => $bogor->_id,
            'status' => 'active'
        ]);

        User::create([
            'name' => 'Admin Cabang Bandung',
            'email' => 'bandung@reservaseat.com',
            'phone' => '083333333333',
            'password' => bcrypt('password123'),
            'photo_profile' => null,
            'role' => 'admin_cabang',
            'cabang_id' => $bandung->_id,
        ]);

        User::create([
            'name' => 'Customer Test',
            'email' => 'customer@reservaseat.com',
            'phone' => '084444444444',
            'password' => bcrypt('password123'),
            'photo_profile' => null,
            'role' => 'customer',
            'cabang_id' => null,
        ]);
    }
}
