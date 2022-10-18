<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComputadoraTelefonoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            [1, 3, 5, 2, '2022-10-07 03:51:17', '2022-10-13 13:02:49'],
            [2, 3, 4, 2, '2022-10-07 03:51:17', '2022-10-13 13:03:06'],
            [3, 3, 5, 2, '2022-10-07 03:51:17', '2022-10-13 13:03:21'],
            [4, 4, 1, 6, '2022-10-07 03:51:17', '2022-10-13 13:03:44'],
            [5, 4, 1, 6, '2022-10-07 03:51:17', '2022-10-13 13:03:05'],
            [185, 3, 4, 2, '2022-10-12 03:56:18', '2022-10-12 03:56:18'],
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `computadoras_telefonos` (`detalle_id`, `memoria_id`, `disco_id`, `procesador_id`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?)', $fila);
        }
    }
}
