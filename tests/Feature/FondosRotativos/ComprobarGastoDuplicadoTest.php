<?php

namespace Tests\Feature\FondosRotativos;

use Tests\TestCase;

class ComprobarGastoDuplicadoTest extends TestCase
{
    /**
     * Test para verificar gastos duplicados.
     * Debe pasar el primero y en el segundo mostrar "El número de factura ya se encuentra registrado"
     *
     * @return void
     */
    public function testVerificarGastoDuplicado()
    {
        $request = [
            "fecha_viat" => "2024-07-29",
            "id_lugar" => 53,
            "lugar" => 53,
            "subTarea" => null,
            "beneficiarios" => [24],
            "ruc" => "1900715440001",
            "factura" => "001-001-000000133",
            "num_comprobante" => null,
            "aut_especial" => 117,
            "detalle" => 1,
            "sub_detalle" => [1, 2],
            "cantidad" => "1",
            "valor_u" => "3",
            "total" => 3,
            "observacion" => "gasto de prueba",
            "comprobante" => "/storage/comprobantesViaticos/uNEJRdyxak.jpeg",
            "comprobante1" => "/storage/comprobantesViaticos/uNEJRdyxak.jpeg",
            "comprobante2" => "/storage/comprobantesViaticos/FWy8KyP58k.jpeg",
            "detalle_estado" => null,
            "id_tarea" => null,
            "id_proyecto" => null,
            "id_usuario" => 162,
            "observacion_anulacion" => null,
            "estado" => 3];
        $response = $this->post('api/fondos-rotativos/gastos', $request);
        $response2 = $this->post('api/fondos-rotativos/gastos', $request);

        $response->assertStatus(200);
        $response2->assertStatus(200);
    }
}
