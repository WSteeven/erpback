<?php

namespace Tests\Feature\Bodega;

use App\Models\MaterialEmpleado;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Throwable;

class NoPermitirMaterialesEmpleadosDuplicadosTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_no_debe_permitir_registros_duplicados()
    {
        $datos = [
            'cantidad_stock' => 10,
            'empleado_id' => 24,
            'detalle_producto_id' => 330,
            'despachado' => 10,
            'cliente_id' => 5,
        ];

        MaterialEmpleado::create($datos); // primer insert

        $this->expectException(QueryException::class); // Laravel debe lanzar una excepción por la restricción única

        MaterialEmpleado::create($datos);
    }



}
