<?php /** @noinspection ALL */

namespace Database\Seeders\RecursosHumanos\RecursosHumanos\SeleccionContratacion;

use App\Http\Resources\Ventas\ModalidadResource;
use App\Models\RecursosHumanos\SeleccionContratacion\Modalidad;
use App\Models\RecursosHumanos\SeleccionContratacion\TipoPuesto;
use Illuminate\Database\Seeder;

class TipoPuestoTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoPuesto::upsert([
            ['nombre' => 'NUEVO'],
            ['nombre' => 'VACANTE'],
            ['nombre' => 'PASANTE'],
        ], uniqueBy:['id'], update:['nombre']);

        Modalidad::firstOrCreate(['nombre' => 'PRESENCIAL']);
        Modalidad::firstOrCreate(['nombre' => 'HIBRIDO']);
        Modalidad::firstOrCreate(['nombre' => 'REMOTO']);
    }
}
