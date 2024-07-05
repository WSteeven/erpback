<?php

namespace Tests\Feature;

use App\Models\UnidadMedida;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnidadMedidaTest extends TestCase
{
    // Usar RefreshDatabase para resetear la base de datos después de cada prueba.
    // Procede con precaución (NO UTILIZAR EN PRODUCCION), habilitar esto borra todos los datos de la base de datos
    // use RefreshDatabase; 

    /** @test */
    public function puede_crearse_una_unidad_de_medida()
    {
        // Creamos una nueva unidad de medida
        $unidadDeMedida = UnidadMedida::create([
            'nombre' => 'Metro',
            'simbolo' => 'M',
        ]);

        // Verificar que la unidad de medida se haya creado correctamente en la base de datos
        $this->assertDatabaseHas('unidades_medidas', [
            'nombre' => 'Metro',
            'simbolo' => 'M',
        ]);
    }
}
