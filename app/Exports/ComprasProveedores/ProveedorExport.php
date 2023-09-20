<?php

namespace App\Exports\ComprasPRoveedores;

use App\Models\Proveedor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProveedorExport implements FromView
{
    protected $reporte;

    function __construct($reporte)
    {
        $this->reporte = $reporte;
    }

    public function view():View
    {
        return view('compras_proveedores.proveedores.excel.proveedores', $this->reporte);
    }

    // /**
    //  * @return \Illuminate\Support\Collection
    //  */
    // public function collection()
    // {
    //     return Proveedor::all();
    // }
}
