<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Master',
            'email' => 'admin@reservaseat.com',
            'phone' => '081111111111',
            'password' => bcrypt('password123'),
            'role' => 'admin_master',
        ]);

        User::create([
            'name' => 'Admin Cabang',
            'email' => 'cabang@reservaseat.com',
            'phone' => '082222222222',
            'password' => bcrypt('password123'),
            'role' => 'admin_cabang',
        ]);

        User::create([
            'name' => 'Customer Demo',
            'email' => 'customer@reservaseat.com',
            'phone' => '083333333333',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);
    }
}