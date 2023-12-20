<?php

namespace App\Exports\ComprasProveedores;

use App\Models\ConfiguracionGeneral;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ProveedorExport implements WithMultipleSheets
{
    use Exportable;

    protected $proveedores;
    protected $contactos;
    protected $datosBancarios;
    protected $configuracion;
    protected $proveedoresCompletos;

    public function __construct($proveedores, $contactos, $datosBancarios, $configuracion, $proveedoresCompletos)
    {
        $this->configuracion = $configuracion;
        $this->proveedores = $proveedores;
        $this->contactos = $contactos;
        $this->datosBancarios = $datosBancarios;
        $this->proveedoresCompletos = $proveedoresCompletos;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[1] = new ProveedoresExport($this->proveedores, $this->configuracion);
        $sheets[2] = new ContactosExport($this->contactos, $this->configuracion);
        $sheets[3] = new DatosBancariosExport($this->datosBancarios, $this->configuracion);
        $sheets[4] = new ProveedoresCompletosExport($this->proveedoresCompletos, $this->configuracion);

        return $sheets;
    }
}
class ProveedoresExport extends DefaultValueBinder implements FromView, WithTitle, WithCustomValueBinder, ShouldAutoSize, WithColumnWidths
{

    protected $proveedores;
    protected $configuracion;

    public function __construct($proveedores, $configuracion)
    {
        $this->proveedores = $proveedores;
        $this->configuracion = $configuracion;
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
            'A' => 30,
            'B' => 30,
        ];
    }
    public function view(): View
    {
        return view('compras_proveedores.proveedores.excel.proveedores', [
            'reporte' => $this->proveedores,
            'configuracion' => $this->configuracion,
        ]);
    }

    public function title(): string
    {
        return 'Proveedores'; // Nombre de la primera hoja
    }
}
class ContactosExport extends DefaultValueBinder implements FromView, WithTitle, WithCustomValueBinder, ShouldAutoSize, WithColumnWidths
{

    protected $contactos;
    protected $configuracion;

    public function __construct($contactos, $configuracion)
    {
        $this->contactos = $contactos;
        $this->configuracion = $configuracion;
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
            'A' => 30,
            'B' => 30,
        ];
    }
    public function view(): View
    {
        return view('compras_proveedores.proveedores.excel.contactos', ['reporte' => $this->contactos, 'configuracion' => $this->configuracion,]);
    }

    public function title(): string
    {
        return 'Contactos de Proveedores'; // Nombre de la segunda hoja
    }
}
class DatosBancariosExport extends DefaultValueBinder implements WithCustomValueBinder, FromView, WithTitle, ShouldAutoSize, WithColumnWidths
{

    protected $datos;
    protected $configuracion;

    public function __construct($datos, $configuracion)
    {
        $this->datos = $datos;
        $this->configuracion = $configuracion;
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
            'A' => 30,
            'B' => 30,
        ];
    }
    public function view(): View
    {
        return view('compras_proveedores.proveedores.excel.datos_bancarios', ['reporte' => $this->datos, 'configuracion' => $this->configuracion,]);
    }

    public function title(): string
    {
        return 'Datos Bancarios'; // Nombre de la segunda hoja
    }
}

class ProveedoresCompletosExport extends DefaultValueBinder implements  WithCustomValueBinder, FromView, ShouldAutoSize, WithColumnWidths{
    protected $datos;
    protected $configuracion;

    public function __construct($datos, $configuracion)
    {
        $this->datos = $datos;
        $this->configuracion = $configuracion;
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
            'A' => 30,
            'B' => 30,
        ];
    }
    public function view(): View
    {
        return view('compras_proveedores.proveedores.excel.datos_completos', ['reporte' => $this->datos, 'configuracion' => $this->configuracion,]);
    }
}
