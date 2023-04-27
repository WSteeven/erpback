<?php

namespace Database\Factories\FondosRotativos\Gasto;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gasto>
 */
class GastoFactory extends Factory
{
    private $cantidad = 0;
    private $valor_u = 0;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->setCantidad($this->faker->numberBetween(1, 13));
        $this->setValorU($this->faker->numberBetween(1, 13));
        return [
            'fecha_viat' =>  fake()->dateTimeBetween($startDate = '-1 month', $endDate = 'now')->format('Y-m-d'),
            'id_lugar' => fake()->numberBetween(1, 29),
            'id_tarea' => fake()->numberBetween(1, 5),
            'id_proyecto' => fake()->numberBetween(1, 2),
            'ruc' =>  $this->RandomRUC(),
            'factura' => $this->RandomFactura(),
            'aut_especial' => fake()->numberBetween(28, 30),
            'detalle' => 1,
            'sub_detalle' => 1,
            'caantidad' => $this->cantidad,
            'valor_u' => $this->valor_u,
            'total' => $this->cantidad * $this->valor_u,
            'comprobante' => fake()->imageUrl(150,150),
            'comprobante2' => fake()->imageUrl(150,150),
            'observacion' => fake()->text(200),
            'id_usuario' => fake()->numberBetween(3, 30),
            'estado' => 1,
            'detalle_estado' => fake()->text(200),
            'created_at' =>  date('Y-m-d H:i:s'),
            'updated_at'  =>  date('Y-m-d H:i:s'),
        ];
    }
    private function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }
    private function setValorU($valor_u)
    {
        $this->valor_u = $valor_u;
    }
    private function RandomRUC()
    {
        $randstring = '0';
        for ($i = 0; $i < 9; $i++) {
            $randstring = $randstring . rand(0, 9);
        }
        return $randstring . '001';
    }
    private  function RandomFactura()
    {
        $randstring = '001';
        $randstring = $randstring . '-';
        $randstring = $randstring . '001-';
        for ($i = 0; $i < 9; $i++) {
            $randstring = $randstring . rand(0, 9);
        }
        return $randstring;
    }
}
