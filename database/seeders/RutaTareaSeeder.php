<?php

namespace Database\Seeders;

use App\Models\RutaTarea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RutaTareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RutaTarea::insert([
            ['ruta' => 'EL CARMEN - STO. DOMINGO', 'cliente_id' => 3],
            ['ruta' => 'PV. MALDONADO - TANDAYAPA', 'cliente_id' => 3],
            ['ruta' => 'LA CONCORDIA - PV. MALDONADO', 'cliente_id' => 3],
            ['ruta' => 'ST. DOMINGO - LA CONCORDIA', 'cliente_id' => 3],
            ['ruta' => 'P. PILAR - ST. DOMINGO', 'cliente_id' => 3],
            ['ruta' => 'LA CONCORDIA URBANO', 'cliente_id' => 3],
            ['ruta' => 'PUERTO QUITO URBANO', 'cliente_id' => 3],
            ['ruta' => 'PV. MALDONADO URBANO', 'cliente_id' => 3],
            ['ruta' => 'STO. DOMINGO URBANO', 'cliente_id' => 3],
            ['ruta' => 'SM. DE LOS BANCOS URBANO', 'cliente_id' => 3],
            ['ruta' => 'QUININDE - LA CONCORDIA', 'cliente_id' => 3],
            ['ruta' => 'ESMERALDAS - ATACAMES', 'cliente_id' => 3],
            ['ruta' => 'ESMERALDAS URBANO', 'cliente_id' => 3],
            ['ruta' => 'ATACAMES URBANO', 'cliente_id' => 3],
            ['ruta' => 'ESMERALDAS - TONCHIGUE', 'cliente_id' => 3],
            ['ruta' => 'ESMERALDAS - VICHE', 'cliente_id' => 3],
            ['ruta' => 'VICHE URBANO', 'cliente_id' => 3],
            ['ruta' => 'VICHE - QUININDE', 'cliente_id' => 3],
            ['ruta' => 'QUICAÑAR - AZOGUES', 'cliente_id' => 3],
            ['ruta' => 'ALAUSI - ZHUD', 'cliente_id' => 3],
            ['ruta' => 'ZHUD - CAÑAR', 'cliente_id' => 3],
            ['ruta' => 'CAÑAR URBANO', 'cliente_id' => 3],
            ['ruta' => 'ZHUD URBANO', 'cliente_id' => 3],
            ['ruta' => 'AZOGUES URBANO', 'cliente_id' => 3],
            ['ruta' => 'PEDERNALES URBANO', 'cliente_id' => 3],
            ['ruta' => 'PEDER-JAMA', 'cliente_id' => 3],
            ['ruta' => 'JAMA-URBANO', 'cliente_id' => 3],
            ['ruta' => 'JAMA-SAN VICENTE', 'cliente_id' => 3],
            ['ruta' => 'TONCHIGUE-AGUA CLARAS', 'cliente_id' => 3],
            ['ruta' => 'AGUAS CLARAS-BECHE', 'cliente_id' => 3],
            ['ruta' => 'MUISNE', 'cliente_id' => 3],
            ['ruta' => 'CHAMANGA', 'cliente_id' => 3],
            ['ruta' => 'GUALACEO - PAUTE', 'cliente_id' => 3],
            ['ruta' => 'GUALACEO URBANO', 'cliente_id' => 3],
            ['ruta' => 'LA UNÍÓN - GUALACEO', 'cliente_id' => 3],
            ['ruta' => 'LA UNIÓN - SIGSIG', 'cliente_id' => 3],
            ['ruta' => 'PAUTE - AZOGUES', 'cliente_id' => 3],
            ['ruta' => 'PAUTE URBANO', 'cliente_id' => 3],
            ['ruta' => 'SIGSIG - CHIGUINDA', 'cliente_id' => 3],
            ['ruta' => 'SIGSIG URBANO', 'cliente_id' => 3],
            ['ruta' => 'AZOGUES URBANO', 'cliente_id' => 3],
        ]);
    }
}
