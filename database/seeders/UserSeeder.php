<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@kindergarten.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Usuario Regular',
            'email' => 'user@kindergarten.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);
    }
}
