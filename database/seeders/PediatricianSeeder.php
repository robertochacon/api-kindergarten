<?php

namespace Database\Seeders;

use App\Models\Pediatrician;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PediatricianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pediatrician::create([
            'range' => 'Pediatra General',
            'name' => 'Dr. María González',
            'identification' => '12345678'
        ]);

        Pediatrician::create([
            'range' => 'Pediatra Especialista',
            'name' => 'Dr. Carlos Rodríguez',
            'identification' => '87654321'
        ]);
    }
}
