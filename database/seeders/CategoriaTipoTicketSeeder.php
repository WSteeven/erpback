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
                'nombre' => 'Soporte técnico',
                'departamento_id' => 6,
            ],
            [
                'nombre' => 'Configuración y personalización',
                'departamento_id' => 6,
            ],
            [
                'nombre' => 'Gestión de accesos y autorizaciones',
                'departamento_id' => 6,
            ],
            [
                'nombre' => 'Capacitación y orientación',
                'departamento_id' => 6,
            ],
            [
                'nombre' => 'Mantenimiento y actualizaciones',
                'departamento_id' => 6,
            ],
            [
                'nombre' => 'Seguridad informática',
                'departamento_id' => 6,
            ],
        ]);
    }
}
