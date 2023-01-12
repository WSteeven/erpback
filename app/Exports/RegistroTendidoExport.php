<?php

namespace App\Exports;

use App\Models\RegistroTendido;
use Maatwebsite\Excel\Concerns\FromCollection;
// Cabecera
use Maatwebsite\Excel\Concerns\WithHeadings;
// Estilos
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
// Columna autosize
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// Dibujos
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
// Iniciar desde una celda en específico
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class RegistroTendidoExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize //, WithDrawings, WithCustomStartCell
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $campos = [
            'numero_elemento',
            'tipo_elemento',
            'propietario_elemento',
            'codigo_elemento',
            'coordenada_del_elemento_latitud',
            'coordenada_del_elemento_longitud',
            'progresiva_entrada',
            'progresiva_salida',
            'cantidad_reserva',
            'cantidad_transformadores',
            'cantidad_retenidas',
            'instalo_manga',
            'observaciones',
            'tension',
            'propietario_americano',
            'coordenada_cruce_americano_latitud',
            'coordenada_cruce_americano_longitud',
            'coordenada_poste_anclaje1_latitud',
            'coordenada_poste_anclaje1_longitud',
            'coordenada_poste_anclaje2_latitud',
            'coordenada_poste_anclaje2_longitud',
            'estado_elemento',
        ];

        return RegistroTendido::select($campos)->orderBy('numero_elemento')->get();
    }

    public function headings(): array
    {
        return [
            'Número de elemento',
            'Tipo de elemento',
            'Propietario',
            'Código del elemento',
            'Coordenada del elemento (latitud)',
            'Coordenada del elemento (longitud)',
            'Progresiva de entrada',
            'Progresiva de salida',
            'Reserva (m)',
            'Cantidad de transformadores',
            'Cantidad de retenidas',
            'Instaló manga',
            'Observaciones',
            'Tensión',
            'Propietario americano',
            'Coordenada de cruce americano (latitud)',
            'Coordenada de cruce americano (longitud)',
            'Coordenada poste anclaje 1 (latitud)',
            'Coordenada poste anclaje 1 (longitud)',
            'Coordenada poste anclaje 2 (latitud)',
            'Coordenada poste anclaje 2 (longitud)',
            'Estado del elemento',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    /* public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/img/logoJP.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('B2');

        return $drawing;
    } */

    /* public function startCell(): string
    {
        return 'A8';
    } */
}
