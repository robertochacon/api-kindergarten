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
            'range' => 'CAPITÁN DE COBERTA - DR. GREGORIO FLORIAN MONTES',
            'name' => 'Dr. Gregorio Florian Montes',
            'phone' => '(849)-257-4016'
        ]);

        Pediatrician::create([
            'range' => 'TENIENTE DE NAVIO - DRA. PATRICIA PACHECO PÉREZ',
            'name' => 'Dr. Patricia Pacheco Pérez',
            'phone' => '(829)-764-1611'
        ]);

        Pediatrician::create([
            'range' => 'SARGENTO - DRA. JESSICA TEJERA VERAS',
            'name' => 'Dr. Jessica Tejera Veras',
            'phone' => '(809)-306-6817'
        ]);
    }
}
