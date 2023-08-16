<?php

namespace Database\Seeders;

use App\Models\MotivoPausaTicket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotivoPausaTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MotivoPausaTicket::insert([
            ['motivo' => 'Esperando información adicional'],
            ['motivo' => 'Dependencia de terceros'],
            ['motivo' => 'Investigación adicional'],
            ['motivo' => 'Priorización de tareas'],
            ['motivo' => 'Esperando aprobaciones'],
            ['motivo' => 'Esperando disponibilidad de recursos'],
        ]);
    }
}
