<?php

namespace Tests\Feature\Bodega;

use App\Models\Comprobante;
use App\Models\TransaccionBodega;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ModificarItemEgresoTest extends TestCase
{

    public function testVerificarEgresoCompletado()
    {
        $completado = Comprobante::verificarEgresoCompletado(3734);
        $this->assertTrue($completado);
    }

    public function testVerificarEgresoIncompleto()
    {
        $completado = Comprobante::verificarEgresoCompletado(4625);
        $this->assertTrue(!$completado);
    }

    /**
     * Este test aún no ha sido configurado ni probado
     */
    // public function editar_egreso_parcial_cantidad_superior_a_la_despachada(){
    //     $request = [
    //         'tipo'=> 'PARCIAL',

    //         'item'=> [

    //         ],
    //     ];
    //     $response = $this->patch('api/modificar-item-egreso', $request);

    //     $response->assertStatus(200);
    // }
}
