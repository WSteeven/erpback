<?php

namespace Src\App\ActivosFijos;

use App\Models\ActivosFijos\SeguimientoConsumoActivosFijos;
use Illuminate\Database\Eloquent\Collection;

class SeguimientoConsumoActivosFijosService
{
    /**
     * Devuelve el historial de consumo de un activo fijo ($detalle_producto_id, $cliente_id)
     * @param int $detalle_producto_id
     * @param int $cliente_id
     */
    public function seguimientoConsumoActivosFijos(): Collection
    {
        $results = SeguimientoConsumoActivosFijos::filter()->get();
        return $results;
    }
}
