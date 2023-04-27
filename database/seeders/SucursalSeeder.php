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
            [1, 'BODEGA  1', '0965421', NULL, 'oficina_matriz@jpconstrucred.com', '2023-03-25 09:08:24', '2023-04-03 14:16:18'],
            [2, 'SANTO DOMINGO', '0965421', NULL, 'oficina_santo_domingo@jpconstrucred.com', '2023-03-25 09:08:24', '2023-03-25 09:08:24'],
            [3, 'CUENCA', '0965421', NULL, 'oficina_cuenca@jpconstrucred.com', '2023-03-25 09:08:24', '2023-03-25 09:08:24'],
            [4, 'GUAYAQUIL', '0965421', NULL, 'oficina_guayaquil@jpconstrucred.com', '2023-03-25 09:08:24', '2023-03-25 09:08:24'],
            [5, 'BODEGA  2', '1234567896', NULL, 'bodega_mch@jpconstrucred.com', '2023-03-25 20:48:43', '2023-04-03 14:16:06'],
            [6, 'BODEGA 24 MAYO', '1234567890', NULL, 'bodega_mch@jpconstrucred.com', '2023-03-25 20:50:04', '2023-04-03 14:15:57'],
            [7, 'RIOBAMBA', '0989671240', NULL, 'mlema@jpconstrucred.com', '2023-04-03 15:53:08', '2023-04-03 15:53:08'],
            [8, 'AMBATO', '0988776568', NULL, 'jtenesaca@jeanpazmino.com', '2023-04-03 18:36:03', '2023-04-03 18:36:03'],
            [9, 'LAGO AGRIO', '0990059309', NULL, 'sbone@jpconstrucred.com', '2023-04-03 18:47:14', '2023-04-03 18:47:14'],
            [10, 'JOYA DE LOS SACHAS', '0959281459', NULL, 'epereira@jpconstrucred.com', '2023-04-03 18:51:13', '2023-04-04 21:49:56'],
            [11, 'YANTZAZA', '0960921261', NULL, 'grupo.yantzaza@jpconstrucred.com', '2023-04-05 18:16:10', '2023-04-05 18:16:10'],
            [12, 'VENTANAS', '0988778105', NULL, 'econdoy@jpconstrucred.com', '2023-04-10 20:15:35', '2023-04-10 20:15:35'],
            [13, 'MOMPICHE', '0988789213', NULL, 'atigua@jeanpazmino.com', '2023-04-10 21:07:55', '2023-04-11 21:42:30'],
            [14, 'QUEVEDO', '0988777560', NULL, 'njara@jeanpazmino.com', '2023-04-11 21:27:39', '2023-04-11 21:27:39'],
            [15, 'LOJA', '0988777134', NULL, 'grupo.loja@jpconstrucred.com', '2023-04-11 21:30:16', '2023-04-11 21:30:16'],
            [16, 'ESMERALDAS', '0988776684', NULL, 'asalas@jpconstrucred.com', '2023-04-11 21:33:59', '2023-04-11 21:33:59'],
            [17, 'QUININDE', '0987725124', NULL, 'jaguilar@jeanpazmino.com', '2023-04-11 21:36:05', '2023-04-11 21:36:05'],
            [18, 'PEDERNALES', '0988777396', NULL, 'achamba@jpconstrucred.com', '2023-04-11 21:40:14', '2023-04-11 21:40:14'],
            [19, 'BALSAS', '0939556768', NULL, 'paguilar@jpconstrucred.com', '2023-04-11 21:41:09', '2023-04-11 21:41:09'],
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `sucursales` (`id`, `lugar`, `telefono`, `extension`, `correo`, `created_at`, `updated_at`) VALUES(?,?,?,?,?,?,?)', $fila);
        }
    }
}
