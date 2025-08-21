<?php

namespace Src\Shared\Charts;

class GraficoEChart
{

    public static function grafico(string $title, string $subtitle, array $leyendas, array $labelsXAxis, array|Series $series): array
    {
        $option = [
            'title' => [
                'text' => $title,
                'subtext' => $subtitle,
                'left' => 'center',
                'top' => 'top',
                'textStyle' => [
                    'fontSize' => 18,
                    'fontWeight' => 'bold'
                ],
                'subtextStyle' => [
                    'color'=> '#175ce5',
                    'fontSize' => 14
                ]
            ],
            'tooltip' => [
                'trigger' => 'axis',
                'axisPointer' => [
                    'type' => 'shadow'
                ]
            ],
            'legend' => [
                'data' => $leyendas,
                'orient' => 'horizontal',
                'bottom' => 0,
            ],
            'toolbox' => [
                'show' => true,
                'orient' => 'vertical',
                'left' => 'right',
                'top' => 'center',
                'feature' => [
                    'mark' => ['show' => true],
                    'dataView' => ['show' => true, 'readOnly' => false],
                    'magicType' => ['show' => true, 'type' => ['line', 'bar', 'stack']],
                    'restore' => ['show' => true],
                    'saveAsImage' => ['show' => true],
                ]
            ],
            'xAxis' => [
                [
                    'type' => 'category',
                    'axisLabel'=>[
                      'rotate' => 90,
                        'fontSize' => 8,
                    ],
                    'axisTick' => ['show' => false],
                    'data' => $labelsXAxis
                ]
            ],
            'yAxis' => [
                [
                    'type' => 'value'
                ]
            ],
            'series' => $series
        ];

        return $option;
    }
}
