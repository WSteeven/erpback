<?php

namespace Tests\Feature;

use App\Models\Canton;
use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\Proyecto;
use App\Models\Subtarea;
use App\Models\Tarea;
use Faker\Factory;
use Tests\TestCase;

class CreateExpensesTest extends TestCase
{
    private $cantidad=0;
    private  $valor_u =0;
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
      $expenseData =[
        'fecha_viat' => $faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now')->format('Y-m-d'),
        'id_lugar' => Canton::inRandomOrder()->value('id') ,
        'id_tarea' => Tarea::inRandomOrder()->value('id') ,
        'id_proyecto' => Proyecto::inRandomOrder()->value('id'),
        'ruc' => $this->randomRUC(),
        'factura' => $this->randomFactura(),
        'aut_especial' =>$faker->numberBetween(28, 30),
        'detalle' =>  DetalleViatico::inRandomOrder()->value('id'),
        'cantidad' => $this->cantidad,
        'valor_u' => $this->valor_u,
        'total' => $this->cantidad * $this->valor_u,
        'comprobante' => $faker->imageUrl(150,150),
        'comprobante2' =>  $faker->imageUrl(150,150),
        'observacion' =>$faker->text(200),
        'id_usuario' =>Empleado::inRandomOrder()->value('id'),
        'estado' => Gasto::PENDIENTE,
        'detalle_estado' => null
      ];


      //Act
      $response =$this->post('api/fondos-rotativos/gastos', $expenseData);

      //Assert
      $response->assertStatus(500);
      $this->assertDatabaseHas('gastos', $expenseData);
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
