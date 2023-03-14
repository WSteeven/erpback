<?php

namespace Database\Factories\FondosRotativos\Saldo;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Acreditaciones>
 */
class AcreditacionesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'fecha' => fake()->dateTimeBetween($startDate = '-1 month', $endDate = 'now')->format('Y-m-d'),
            'id_saldo' => $this->RandomSaldo(),
            'descripcion_saldo' =>fake()->text(200),
            'monto' =>fake()->numberBetween(20, 200),
            'id_usuario' => fake()->numberBetween(3, 30),
            'id_tipo_saldo' =>fake()->numberBetween(1,4),
            'id_tipo_fondo' => fake()->numberBetween(1,2),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' =>date('Y-m-d H:i:s'),
        ];
    }
    private function RandomSaldo()
    {
        $randstring = '0';
        for ($i = 0; $i < 9; $i++) {
            $randstring = $randstring . rand(0, 9);
        }
        return $randstring;
    }
}
