<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoAcreditacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estados = [
            'REALIZADO',
            'ANULADO',
        ];

        foreach ($estados as $estado) {
            DB::table('estado_acreditaciones')->insert([
                'estado' => $estado,
            ]);
        }
    }
}
