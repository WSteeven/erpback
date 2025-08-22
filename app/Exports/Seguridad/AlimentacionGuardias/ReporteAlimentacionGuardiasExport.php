<?php

namespace App\Exports\Seguridad\AlimentacionGuardias;

use App\Models\Seguridad\Bitacora;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;


class ReporteAlimentacionGuardiasExport extends DefaultValueBinder implements FromView, WithCustomValueBinder, ShouldAutoSize, WithColumnWidths, WithTitle
{
    protected $filtros;
    protected $vista;

    protected $titulo_reporte;

    public function __construct(array $filtros, string $vista, string $tituloHoja = 'Reporte')
    {
        $this->filtros = $filtros;
        $this->vista = $vista;

        // Limpieza adicional del título
        $tituloHoja = preg_replace('/[^\w\s]/', '', $tituloHoja); // Elimina caracteres especiales
        $this->titulo_reporte = mb_substr(trim($tituloHoja), 0, 28); // Dejamos margen de seguridad
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 33,
            'C' => 10,
            'D' => 10,
            'E' => 22,
            'F' => 22,
            'G' => 87,
            'H' => 60,
            'I' => 25,
            'J' => 25,
            'K' => 27,
            'L' => 10,
        ];
    }

    public function view(): View
    {
        return view($this->vista, $this->filtros);
    }


    public function title(): string
    {
        // Fuerza un título seguro si detecta problemas
        if (mb_strlen($this->titulo_reporte) > 31) {
            return 'Reporte Guardia';
        }
        return $this->titulo_reporte;
    }
}
