<?php

namespace Database\Seeders;

use App\Models\Ventas\Bono;
use Illuminate\Database\Seeder;

class BonosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bono::insert([
            ['cant_ventas' => 6,'valor'=>20],
            ['cant_ventas' => 12,'valor'=>50],
            ['cant_ventas' => 16,'valor'=>75],
            ['cant_ventas' => 6,'valor'=>20],
            ['cant_ventas' => 12,'valor'=>50],
            ['cant_ventas' => 16,'valor'=>75],
        ]);
    }
}
