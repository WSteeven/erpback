<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\Periodo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Periodo::insert([
            ['nombre' => '2023-2024','activo'=>true],
            ['nombre' => '2024-2025','activo' => false]
        ]);
    }
}
