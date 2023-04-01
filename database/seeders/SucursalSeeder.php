<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Localidad o sucursal
        // Sucursal::create(['lugar' => 'MACHALA', 'telefono' => '0965421', 'correo' => 'oficina_matriz@jpconstrucred.com',]);
        // Sucursal::create(['lugar' => 'SANTO DOMINGO', 'telefono' => '0965421', 'correo' => 'oficina_santo_domingo@jpconstrucred.com',]);
        // Sucursal::create(['lugar' => 'CUENCA', 'telefono' => '0965421', 'correo' => 'oficina_cuenca@jpconstrucred.com',]);
        // Sucursal::create(['lugar' => 'GUAYAQUIL', 'telefono' => '0965421', 'correo' => 'oficina_guayaquil@jpconstrucred.com',]);
        $datos = [
            [1, 'MACHALA 1', '0965421', NULL, 'oficina_matriz@jpconstrucred.com', '2023-03-25 04:08:24', '2023-03-25 15:47:32'],
            [2, 'SANTO DOMINGO', '0965421', NULL, 'oficina_santo_domingo@jpconstrucred.com', '2023-03-25 04:08:24', '2023-03-25 04:08:24'],
            [3, 'CUENCA', '0965421', NULL, 'oficina_cuenca@jpconstrucred.com', '2023-03-25 04:08:24', '2023-03-25 04:08:24'],
            [4, 'GUAYAQUIL', '0965421', NULL, 'oficina_guayaquil@jpconstrucred.com', '2023-03-25 04:08:24', '2023-03-25 04:08:24'],
            [5, 'machala 2', '1234567896', NULL, 'bodega_mch@jpconstrucred.com', '2023-03-25 15:48:43', '2023-03-25 15:48:43'],
            [6, 'machala 24 mayo', '1234567890', NULL, 'bodega_mch@jpconstrucred.com', '2023-03-25 15:50:04', '2023-03-25 15:50:04'],
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `sucursales` (`id`, `lugar`, `telefono`, `extension`, `correo`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?)', $fila);
        }
    }
}
