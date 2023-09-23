<?php

namespace App\Exports\ComprasProveedores;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
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

    public function __construct($proveedores, $contactos, $datosBancarios)
    {
        $this->proveedores = $proveedores;
        $this->contactos = $contactos;
        $this->datosBancarios = $datosBancarios;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[1] = new ProveedoresExport($this->proveedores);
        $sheets[2] = new ContactosExport($this->contactos);
        $sheets[3] = new DatosBancariosExport($this->datosBancarios);

        return $sheets;
    }
}
class ProveedoresExport extends DefaultValueBinder implements FromView, WithTitle, WithCustomValueBinder
{

    protected $proveedores;

    public function __construct($proveedores)
    {
        $this->proveedores = $proveedores;
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
    
    public function view(): View
    {
        return view('compras_proveedores.proveedores.excel.proveedores', ['reporte' => $this->proveedores,]);
    }

    public function title(): string
    {
        return 'Proveedores'; // Nombre de la primera hoja
    }
}
class ContactosExport extends DefaultValueBinder implements FromView, WithTitle, WithCustomValueBinder
{

    protected $contactos;

    public function __construct($contactos)
    {
        $this->contactos = $contactos;
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

    public function view(): View
    {
        return view('compras_proveedores.proveedores.excel.contactos', ['reporte' => $this->contactos,]);
    }

    public function title(): string
    {
        return 'Contactos de Proveedores'; // Nombre de la segunda hoja
    }
}
class DatosBancariosExport extends DefaultValueBinder implements WithCustomValueBinder, FromView, WithTitle
{

    protected $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;
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

    public function view(): View
    {
        return view('compras_proveedores.proveedores.excel.datos_bancarios', ['reporte' => $this->datos,]);
    }

    public function title(): string
    {
        return 'Datos Bancarios'; // Nombre de la segunda hoja
    }
}
