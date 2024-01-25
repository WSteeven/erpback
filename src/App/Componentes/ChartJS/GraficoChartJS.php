<?php

namespace Src\App\Componentes\ChartJS;

/**
 * Class GraficoChartJS
 * @package App\Components\ChartJS\Domain
 */
class GraficoChartJS
{
    /**
     * Construir gráficos.
     *
     * @param array $result
     * @return array
     */
    // La lista debe tener la estructura ['clave' => 'CLAVE', 'valor' => valor]
    public static function mapear(array $listado, string $titulo, string $tituloLabel)
    {
        $labels = array_map(fn ($item) => $item['clave'], $listado);
        $valores = array_map(fn ($item) => $item['valor'], $listado);
        $colores = array_map(fn ($item) => self::generarColorAzulPastelClaro(), $listado);

        return self::mapearDatos($titulo, $labels, $valores, $tituloLabel, $colores);
    }

    /**
     * Mapear datos para el gráfico.
     *
     * @param array $labels
     * @param array $valores
     * @param string $titulo
     * @param string|null $colores
     * @return array
     */
    public static function mapearDatos(string $titulo, array $labels, array $valores, string $tituloLabel, $colores = null): array
    {
        return [
            'titulo' => $titulo,
            'labels' => $labels,
            'datasets' => [
                [
                    'backgroundColor' => $colores ?? '#666f88',
                    'label' => $tituloLabel,
                    'data' => $valores,
                ],
            ],
        ];
    }

    /**
     * Generar color azul pastel claro.
     *
     * @return string
     */
    public static function generarColorAzulPastelClaro(): string
    {
        // Generar valores RGB altos (entre 150 y 220) para obtener un tono azul claro
        $r = rand(150, 220);
        $g = rand(150, 220);
        $b = rand(100, 255); // Para asegurarse de que el tono sea azul claro

        // Ajustar el brillo para hacerlo más claro (entre 0.7 y 1.0)
        $brillo = mt_rand(70, 100) / 100.0;

        // Convertir a formato hexadecimal
        $colorHex = sprintf("#%02x%02x%02x", $r, $g, $b);

        // Aplicar el brillo al color hexadecimal
        $colorClaroHex = self::ajustarBrillo($colorHex, $brillo);

        return $colorClaroHex;
    }

    /**
     * Ajustar brillo del color.
     *
     * @param string $colorHex
     * @param float $brillo
     * @return string
     */
    public static function ajustarBrillo(string $colorHex, float $brillo): string
    {
        $r = hexdec(substr($colorHex, 1, 2));
        $g = hexdec(substr($colorHex, 3, 2));
        $b = hexdec(substr($colorHex, 5, 2));

        $rNuevo = round($r * $brillo);
        $gNuevo = round($g * $brillo);
        $bNuevo = round($b * $brillo);

        $colorOscuroHex = sprintf("#%06x", ($rNuevo << 16) | ($gNuevo << 8) | $bNuevo);

        return $colorOscuroHex;
    }
}
