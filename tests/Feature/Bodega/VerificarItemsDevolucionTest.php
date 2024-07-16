<?php

namespace Tests\Feature\Bodega;

use App\Models\DetalleDevolucionProducto;
use App\Models\Devolucion;
use App\Models\EstadoTransaccion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Src\App\Bodega\DevolucionService;
use Tests\TestCase;

class VerificarItemsDevolucionTest extends TestCase
{

    public function test_verificar_items_devolucion()
    {
        $id = 0;

        $detalleDevolucion = DetalleDevolucionProducto::where('devolucion_id', $id)->first();

        $devolucionService = new DevolucionService();
        $devolucionService->verificarItemsDevolucion($detalleDevolucion);

        $devolucion = Devolucion::find($id);
        $this->assertEquals(EstadoTransaccion::COMPLETA, $devolucion->estado_bodega);
    }
}
