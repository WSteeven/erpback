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
                'nombre' => 'Fallas de hardware',
                'categoria_tipo_ticket_id' => 1,
            ],
            [
                'nombre' => 'Fallas de software',
                'categoria_tipo_ticket_id' => 1,
            ],
            [
                'nombre' => 'Problemas de conectividad',
                'categoria_tipo_ticket_id' => 1,
            ],
            [
                'nombre' => 'Problemas de rendimiento',
                'categoria_tipo_ticket_id' => 1,
            ],
            [
                'nombre' => 'Recuperación de datos',
                'categoria_tipo_ticket_id' => 1,
            ],
            [
                'nombre' => 'Instalación de software',
                'categoria_tipo_ticket_id' => 2,
            ],
            [
                'nombre' => 'Configuración de cuentas',
                'categoria_tipo_ticket_id' => 2,
            ],
            [
                'nombre' => 'Personalización de software',
                'categoria_tipo_ticket_id' => 2,
            ],
            [
                'nombre' => 'Creación o modificación de cuentas',
                'categoria_tipo_ticket_id' => 3,
            ],
            [
                'nombre' => 'Restablecimiento de contraseñas',
                'categoria_tipo_ticket_id' => 3,
            ],
            [
                'nombre' => 'Administración de privilegios',
                'categoria_tipo_ticket_id' => 3,
            ],
            [
                'nombre' => 'Uso de software',
                'categoria_tipo_ticket_id' => 4,
            ],
            [
                'nombre' => 'Mejores prácticas de seguridad',
                'categoria_tipo_ticket_id' => 4,
            ],
            [
                'nombre' => 'Herramientas de productividad',
                'categoria_tipo_ticket_id' => 4,
            ],
        ]);
    }
}
