<?php

namespace Tests\Feature\FondosRotativos;

use App\Http\Controllers\FondosRotativos\Gasto\GastoController;
use App\Http\Requests\GastoRequest;
use App\Models\FondosRotativos\Gasto\Gasto;
use Illuminate\Http\Request as IllumRequest;

// <— usa el Request de Illuminate
use Illuminate\Testing\TestResponse;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Throwable;

class AprobarGastoTest extends TestCase
{

    private function makeReq(array $payload): GastoRequest
    {
        $path = '/api/fondos-rotativos/aprobar-gasto';

        // 1) Request de ILLUMINATE (no de Symfony)
        $ill = IllumRequest::create($path, 'POST', $payload);

        // 2) Route match con Illuminate Request
        $route = app('router')->getRoutes()->match($ill);

        // 3) Construir el FormRequest a partir del Illuminate Request
        /** @var GastoRequest $req */
        $req = GastoRequest::createFromBase($ill);
        $req->setContainer(app());
        $req->setRedirector(app('redirect'));

        // 4) Inyectar la ruta para que route()->getActionMethod() funcione
        $req->setRouteResolver(fn() => $route);

        // 5) Ejecutar authorize() + rules()
        $req->validateResolved();

        return $req;
    }

    /** @test */
    public function test_aprueba_dos_veces_mismo_gasto()
    {
        $id = 38835;
        $gasto = Gasto::findOrFail($id);

        $payload = array_merge($gasto->toArray(), [
            'id' => $id,
            'estado' => 1,
            'id_lugar' => 1,
            'lugar' => 1,
            'comprobante' => $gasto->comprobante,
            'comprobante1' => $gasto->comprobante,
            'detalle_estado' => 'REVISADO ALIMENTACION S28', // ojo al typo de "ALIMENTACION"
            'sub_detalle' => [2],
            'beneficiarios' => [],
        ]);

        $controller = app(GastoController::class);

        // 1) Primera aprobación — OK
        $req1 = $this->makeReq($payload);
        $resp1 = $controller->aprobarGasto($req1);
        TestResponse::fromBaseResponse($resp1)
            ->assertOk()
            ->assertJson(['success' => 'Gasto autorizado correctamente']);

        // 2) Segunda aprobación — debe fallar con ValidationException (422)
        // Segunda aprobación: esperamos excepción
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('El gasto ya fue aprobado');

        $req2 = $this->makeReq($payload);
        $controller->aprobarGasto($req2);
    }

}
