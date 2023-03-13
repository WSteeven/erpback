<?php

namespace Database\Seeders;

use App\Models\MotivoSuspendido;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotivoSuspendidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MotivoSuspendido::insert([
            ['motivo' => 'Pendiente por zona peligrosa: ubicado en un lugar que presenta riesgos para la seguridad física, vehículo o materiales.'],
            ['motivo' => 'Problemas de acceso a la zona, no relacionada al clima o a falta de documentación'],
            ['motivo' => 'Cliente no acepta costos adicionales por trabajos no considerados.'],
            ['motivo' => 'Cliente desiste del trabajo solicitado.'],
        ]);
    }
}
