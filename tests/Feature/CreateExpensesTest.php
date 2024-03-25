<?php

namespace Tests\Feature;

use App\Models\Canton;
use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Gasto\SubDetalleViatico;
use App\Models\Proyecto;
use App\Models\Subtarea;
use App\Models\Tarea;
use Faker\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CreateExpensesTest extends TestCase
{
    private $cantidad = 0;
    private  $valor_u = 0;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_an_expenses_can_be_created()
    {
        $faker = Factory::create('es_PE');
        $this->setCantidad($faker->numberBetween(1, 13));
        $this->setValorU($faker->numberBetween(1, 13));
        //Arrange:
        $expenseData = [
            'fecha_viat' => $faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now')->format('Y-m-d'),
            'lugar' => Canton::inRandomOrder()->value('id'),
            'num_tarea' => Tarea::inRandomOrder()->value('id'),
            'proyecto' => Proyecto::inRandomOrder()->value('id'),
            'ruc' => '0704265099001',
            'factura' => $this->randomFactura(),
            'aut_especial' => $faker->numberBetween(28, 30),
            'detalle' =>  DetalleViatico::inRandomOrder()->value('id'),
            'sub_detalle' => $this->generarArraySubDetalle(),
            'cantidad' => $this->cantidad,
            'valor_u' => $this->valor_u,
            'total' => $this->cantidad * $this->valor_u,
            'comprobante' => $faker->imageUrl(150, 150),
            'comprobante2' =>  $faker->imageUrl(150, 150),
            'observacion' => $faker->text(200),
            'id_usuario' => Empleado::inRandomOrder()->value('id'),
            'estado' => Gasto::PENDIENTE,
            'detalle_estado' => null
        ];
        Log::channel('testing')->info('Log', ['expenseData', $expenseData]);

       $usuario = [
            "name" => "hsimbana",
            "password" => "0704265099"
        ];
        $response = Http::post('http://localhost:8000/api/usuarios/login', $usuario);

        $headers = [
            'Authorization' => 'Bearer ' . $response->json()['access_token']
        ];

        //Act
      //  $response = $this->post('api/fondos-rotativos/gastos', $expenseData);
        $response = $this->post('api/fondos-rotativos/gastos', $expenseData, $headers);

        //Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('gastos', $expenseData);
    }

    private function generarArraySubDetalle()
    {
        $array = array();
        for ($x = 1; $x <= 3; $x++) {
            $array[] = SubDetalleViatico::inRandomOrder()->value('id') ;
        }
        return $array;
    }
    private function randomRUC()
    {
        $randstring = '0';
        for ($i = 0; $i < 9; $i++) {
            $randstring = $randstring . rand(0, 9);
        }
        return $randstring . '001';
    }
    private  function randomFactura()
    {
        $randstring = '001';
        $randstring = $randstring . '-';
        $randstring = $randstring . '001-';
        for ($i = 0; $i < 9; $i++) {
            $randstring = $randstring . rand(0, 9);
        }
        return $randstring;
    }
    private function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }
    private function setValorU($valor_u)
    {
        $this->valor_u = $valor_u;
    }
}
