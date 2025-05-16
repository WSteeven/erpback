<?php

namespace App\Helpers\Filtros;

use Exception;

class FiltroSearchHelper
{

    /**
     * Esta funcion recibe un array de filtros con la estructura [clave,valor,operador]
     * @throws Exception
     */
    public static function formatearFiltrosPorMotor(array $filtros, ?string $motor = null): string
    {
        $motor = is_null($motor) ? config('scout.driver') : $motor;

        $formateados = [];
        foreach ($filtros as $i => $filtro) {
            $clave = $filtro['clave'];
            $valor = $filtro['valor'];
            $operador = $filtro['operador'] ?? null;
//            $this->mapearOperador($operador);
            // Formateo por motor
            $expresion = match ($motor) {
                'algolia' => "{$clave}:{$valor}",
                'meilisearch' => "{$clave} = \"" . addslashes($valor) . "\"",
                'typesense' => "{$clave}:=\"" . addslashes($valor) . "\"",
                default => throw new Exception("Motor '$motor' no soportado."),
            };

            // Agregar operador si no es el primero
            if ($i > 0 && $operador) {
                $formateados[] = $operador;
            }

            $formateados[] = $expresion;
        }

        return implode(' ', $formateados);
    }

//    private  function mapearOperador($operador)
//    {
//        $motor = config('scout.driver');
//        return match ($motor) {
//            'meilisearch' => $operador == 'AND' ? '&&' : ($operador == 'OR' ? '||' : null),
//            default => $operador,
//
//        };
//    }

}
