<?php

namespace Database\Seeders;

use App\Models\TipoTicket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoTicket::insert([
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 1,
            ],
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 2,

            ],
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 3,
            ],
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 4,
            ],
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 5,
            ],
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 6,
            ],
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 7,
            ],
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 8,
            ],
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 9,
            ],
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 10,
            ],
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 11,
            ],
            [
                'nombre' => 'Tipo de ticket 1',
                'categoria_tipo_ticket_id' => 12,
            ],
        ]);
    }
}
