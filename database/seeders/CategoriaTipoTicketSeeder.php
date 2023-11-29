<?php

namespace Database\Seeders;

use App\Models\CategoriaTipoTicket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaTipoTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoriaTipoTicket::insert([
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 1,
            ],
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 4,
            ],
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 5,
            ],
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 6,
            ],
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 7,
            ],
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 8,
            ],
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 9,
            ],
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 10,
            ],
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 11,
            ],
            [
                'nombre' => 'Categoria 1',
                'departamento_id' => 12,
            ],
        ]);
    }
}
