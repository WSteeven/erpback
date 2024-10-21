<?php

namespace Database\Seeders\RecursosHumanos\ActivosFijos;

use App\Models\ActivosFijos\MotivoConsumoActivoFijo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotivoConsumoActivoFijoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MotivoConsumoActivoFijo::insert([
            ['nombre' => 'AGRESIÓN DIRECTA', 'categoria_motivo_consumo_activo_fijo_id' => 1],
            ['nombre' => 'AMENAZA INMINENTE', 'categoria_motivo_consumo_activo_fijo_id' => 1],

            ['nombre' => 'PROTECCIÓN DE CLIENTES', 'categoria_motivo_consumo_activo_fijo_id' => 2],
            ['nombre' => 'PROTECCIÓN DE COMPAÑEROS DE TRABAJO', 'categoria_motivo_consumo_activo_fijo_id' => 2],
            ['nombre' => 'PROTECCIÓN DE CIVILES', 'categoria_motivo_consumo_activo_fijo_id' => 2],

            ['nombre' => 'ROBO EN PROCESO', 'categoria_motivo_consumo_activo_fijo_id' => 3],
            ['nombre' => 'ASALTO A MANO ARMADA', 'categoria_motivo_consumo_activo_fijo_id' => 3],
            ['nombre' => 'SECUESTRO', 'categoria_motivo_consumo_activo_fijo_id' => 3],
            ['nombre' => 'VANDALISMO GRAVE', 'categoria_motivo_consumo_activo_fijo_id' => 3],

            ['nombre' => 'DISTURBIOS PÚBLICOS', 'categoria_motivo_consumo_activo_fijo_id' => 4],
            ['nombre' => 'MOTINES', 'categoria_motivo_consumo_activo_fijo_id' => 4],
            ['nombre' => 'ATAQUES TERRORISTAS', 'categoria_motivo_consumo_activo_fijo_id' => 4],

            ['nombre' => 'DISPARO DE ADVERTENCIA PARA DISUADIR AMENAZAS', 'categoria_motivo_consumo_activo_fijo_id' => 5],
            ['nombre' => 'DISPARO AL AIRE', 'categoria_motivo_consumo_activo_fijo_id' => 5],

            ['nombre' => 'ATAQUE DE ANIMALES SALVAJES', 'categoria_motivo_consumo_activo_fijo_id' => 6],
            ['nombre' => 'ANIMALES DOMESTICOS AGRESIVOS', 'categoria_motivo_consumo_activo_fijo_id' => 6],

            ['nombre' => 'ENTRENAMIENTO EN CAMPO DE TIRO', 'categoria_motivo_consumo_activo_fijo_id' => 7],
            ['nombre' => 'PRUEBAS PARA CORRECTO FUNCIONAMIENTO DEL ARMA', 'categoria_motivo_consumo_activo_fijo_id' => 7],

            ['nombre' => 'DISPARO ACCIDENTAL', 'categoria_motivo_consumo_activo_fijo_id' => 8],
            ['nombre' => 'FALLO DEL EQUIPO', 'categoria_motivo_consumo_activo_fijo_id' => 8],

            ['nombre' => 'ÓRDENES SUPERIORES EN SITUACIONES DE RIESGO', 'categoria_motivo_consumo_activo_fijo_id' => 9],
            ['nombre' => 'OPERACIONES ESPECIALES', 'categoria_motivo_consumo_activo_fijo_id' => 9],

            ['nombre' => 'INTRUSIÓN EN PROPIEDAD PRIVADA', 'categoria_motivo_consumo_activo_fijo_id' => 10],
            ['nombre' => 'SABOTAJE', 'categoria_motivo_consumo_activo_fijo_id' => 10],
            ['nombre' => 'DAÑOS A INFRAESTRUCTURA CRÍTICA', 'categoria_motivo_consumo_activo_fijo_id' => 10],

            ['nombre' => 'REVISIÓN DE PERÍMETRO BAJO SOSPECHA', 'categoria_motivo_consumo_activo_fijo_id' => 11],
            ['nombre' => 'RESPUESTA A ALARMAS DE SEGURIDAD', 'categoria_motivo_consumo_activo_fijo_id' => 11],
        ]);
    }
}
