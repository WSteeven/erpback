<?php

namespace Database\Seeders;

use App\Models\MotivoPausa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotivoPausaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MotivoPausa::insert([
            ['motivo' => 'DESAYUNO'],
            ['motivo' => 'ALMUERZO'],
            ['motivo' => 'MERIENDA'],
        ]);
    }
}
