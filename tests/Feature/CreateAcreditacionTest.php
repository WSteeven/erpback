<?php

namespace Tests\Feature;

use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\TipoFondo;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\TipoSaldo;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CreateAcreditacionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_puede_acreditar_saldo()
    {
        $faker = Factory::create('es_PE');

        //Arrange:
        $acreditacion_data = [
            'usuario' =>Empleado::where('estado', '1')->inRandomOrder()->value('id'),
            'tipo_fondo' => TipoFondo::inRandomOrder()->value('id'),
            'tipo_saldo' => TipoSaldo::inRandomOrder()->value('id'),
            'monto' => $faker->numberBetween(1, 13),
            'id_saldo' => $faker->numberBetween(1, 13),
            'descripcion_acreditacion' => $faker->text(200),
        ];
        $usuario = [
            "name" => "amuentes",
            "password" => "0750291361"
        ];
        $response_login = $this->post('http://localhost:8000/api/usuarios/login', $usuario);
        $headers = [
            'Authorization' => 'Bearer ' . $response_login->baseResponse->original['access_token']
        ];
        //Act
        $response = $this->post('api/fondos-rotativos/acreditacion', $acreditacion_data, $headers);

        //Assert
        $response->assertStatus(200);
    }
    public function test_puede_anular_acreditacion(){
        $faker = Factory::create('es_PE');
        //Arrange:
        $acreditacion_data = [
            'id' =>Acreditaciones::where('id_estado', EstadoAcreditaciones::REALIZADO)->inRandomOrder()->value('id'),
            'descripcion_acreditacion' => $faker->text(200),
        ];
        $usuario = [
            "name" => "amuentes",
            "password" => "0750291361"
        ];
        $response_login = $this->post('http://localhost:8000/api/usuarios/login', $usuario);
        $headers = [
            'Authorization' => 'Bearer ' . $response_login->baseResponse->original['access_token']
        ];
        //Act
        $response = $this->post('api/fondos-rotativos/anular-acreditacion', $acreditacion_data, $headers);

        //Assert
        $response->assertStatus(200);
    }
}
