<?php

namespace Database\Seeders;

use App\Models\MotivoPendiente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotivoPendienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MotivoPendiente::insert([
            ['motivo' => 'Causa del cliente: de viaje, aplazamiento, no define fecha, construcción.'],
            ['motivo' => 'Datos erróneos del cliente: dirección, teléfonos, etc'],
            ['motivo' => 'Faltante de materiales.'],
            ['motivo' => 'Falta de disponibilidad técnica: el/los técnicos no alcanzan a llegar a prestar el servicio agendado.'],
            ['motivo' => 'Falta de alguna documentación: no se pudo prestar el servicio por falta de algún documento requerido y que el cliente no tiene.'],
            ['motivo' => 'Problemas climáticos que impiden completar el trabajo'],
            ['motivo' => 'Cliente ilocalizable'],
            ['motivo' => 'Zona peligrosa: ubicado en un lugar que presenta riesgos para la seguridad física, vehículo o materiales.'],
            ['motivo' => 'Problemas de acceso a la zona, no relacionada al clima o a falta de documentación'],
            ['motivo' => 'Cliente no acepta costos adicionales por trabajos no considerados.'],
            ['motivo' => 'Mal asesoramiento en la venta: cliente informa que no ha sido informado correctamente sobre el trabajo a ser realizado.'],
        ]);
    }
}
