<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grupo;
use Illuminate\Support\Facades\DB;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            [1, 'JAIME PILAY', 1, NULL, NULL],
            [2, 'ACCESSNET 1', 1, NULL, NULL],
            [3, 'ACCESSNET 2', 1, NULL, NULL],
            [4, 'AMBATO', 1, NULL, NULL],
            [5, 'BABAHOYO 1', 1, NULL, NULL],
            [6, 'BABAHOYO 2', 1, NULL, NULL],
            [7, 'BALSAS', 1, NULL, NULL],
            [8, 'CABLISTA 1', 1, NULL, NULL],
            [9, 'CABLISTA 2', 1, NULL, NULL],
            [10, 'CABLISTA 3', 1, NULL, NULL],
            [11, 'CAÑAR', 1, NULL, NULL],
            [12, 'CHONE', 1, NULL, NULL],
            [13, 'CUENCA', 1, NULL, NULL],
            [14, 'ESMERALDAS', 1, NULL, NULL],
            [15, 'GRUPO 2 GUAYAQUIL PROCISA', 0, NULL, NULL],
            [16, 'GUALACEO', 1, NULL, NULL],
            [17, 'GUAYAQUIL', 1, NULL, NULL],
            [18, 'JAIRO SEGUICHE', 1, NULL, NULL],
            [19, 'JEAN CARLOS PARRALES', 1, NULL, NULL],
            [20, 'JOYA', 1, NULL, NULL],
            [21, 'JUANPINCAY', 1, NULL, NULL],
            [22, 'LAGO', 1, NULL, NULL],
            [23, 'LATACUNGA', 1, NULL, NULL],
            [24, 'LOJA', 1, NULL, NULL],
            [25, 'MACAS', 1, NULL, NULL],
            [26, 'MACHALA', 1, NULL, NULL],
            [27, 'MEGANET', 1, NULL, NULL],
            [28, 'MOMPICHE', 1, NULL, NULL],
            [29, 'PEDERNALES', 1, NULL, NULL],
            [30, 'QUEVEDO', 1, NULL, NULL],
            [31, 'QUININDE', 1, NULL, NULL],
            [32, 'RIOBAMBA', 1, NULL, NULL],
            [33, 'SAMBORONDON', 0, NULL, NULL],
            [34, 'SANTO DOMINGO', 1, NULL, NULL],
            [35, 'TONCHIGUE', 1, NULL, NULL],
            [36, 'VENTANAS', 1, NULL, NULL],
            [37, 'YANTZAZA', 1, NULL, NULL],
        ];

        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `grupos` (`id`, `nombre`, `activo`, `created_at`, `updated_at`) VALUES(?,?,?,?,?)', $fila);
        }

        /*Grupo::insert([
            [
                'nombre' => 'ACCESSNET 1',
                'activo' => 1,
            ],
            [
                'nombre' => 'ACCESSNET 2',
                'activo' => 1,
            ],
            [
                'nombre' => 'AMBATO',
                'activo' => 1,
            ],
            [
                'nombre' => 'BABAHOYO 1',
                'activo' => 1,
            ],
            [
                'nombre' => 'BABAHOYO 2',
                'activo' => 1,
            ],
            [
                'nombre' => 'BALSAS',
                'activo' => 1,
            ],
            [
                'nombre' => 'CABLISTA 1',
                'activo' => 1,
            ],
            [
                'nombre' => 'CABLISTA 2',
                'activo' => 1,
            ],
            [
                'nombre' => 'CABLISTA 3',
                'activo' => 1,
            ],
            [
                'nombre' => 'CAÑAR',
                'activo' => 1,
            ],
            [
                'nombre' => 'CHONE',
                'activo' => 1,
            ],
            [
                'nombre' => 'CUENCA',
                'activo' => 1,
            ],
            [
                'nombre' => 'ESMERALDAS',
                'activo' => 1,
            ],
            [
                'nombre' => 'GUALACEO',
                'activo' => 1,
            ],
            [
                'nombre' => 'GUAYAQUIL',
                'activo' => 1,
            ],
            [
                'nombre' => 'JAIME PILAY',
                'activo' => 1,
            ],
            [
                'nombre' => 'JAIRO SEGUICHE',
                'activo' => 1,
            ],
            [
                'nombre' => 'JEAN CARLOS PARRALES',
                'activo' => 1,
            ],
            [
                'nombre' => 'JOYA',
                'activo' => 1,
            ],
            [
                'nombre' => 'JUANPINCAY',
                'activo' => 1,
            ],
            [
                'nombre' => 'LAGO',
                'activo' => 1,
            ],
            [
                'nombre' => 'LATACUNGA',
                'activo' => 1,
            ],
            [
                'nombre' => 'LOJA',
                'activo' => 1,
            ],
            [
                'nombre' => 'MACAS',
                'activo' => 1,
            ],
            [
                'nombre' => 'MACHALA',
                'activo' => 1,
            ],
            [
                'nombre' => 'MEGANET',
                'activo' => 1,
            ],
            [
                'nombre' => 'MOMPICHE',
                'activo' => 1,
            ],
            [
                'nombre' => 'PEDERNALES',
                'activo' => 1,
            ],
            [
                'nombre' => 'QUEVEDO',
                'activo' => 1,
            ],
            [
                'nombre' => 'QUININDE',
                'activo' => 1,
            ],
            [
                'nombre' => 'RIOBAMBA',
                'activo' => 1,
            ],
            [
                'nombre' => 'SANTO DOMINGO',
                'activo' => 1,
            ],
            [
                'nombre' => 'TONCHIGUE',
                'activo' => 1,
            ],
            [
                'nombre' => 'VENTANAS',
                'activo' => 1,
            ],
            [
                'nombre' => 'YANTZAZA',
                'activo' => 1,
            ],
        ]);*/
    }
}
