<?php

namespace App\Imports\ComprasProveedores;

use App\Models\ComprasProveedores\ItemPagoProveedores;
use App\Models\ComprasProveedores\PagoProveedores;
use Maatwebsite\Excel\Concerns\ToModel;

class PagoProveedoresImport implements ToModel
{
    public int $filas_validas = 0;
    public PagoProveedores $pago;
    public function __construct($pago)
    {
        $this->pago = $pago;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Log::channel('testing')->info('Log', ['Fila en import', $row]);
        $cant_nulos = 0;
        foreach ($row as $key => $r) {
            // Log::channel('testing')->info('Log', ['Es numero', $key, $r, doubleval($r),]);
            if (is_null($r)) $cant_nulos++;
            if ($key == 19) {
                if ($cant_nulos == 0) {
                    // Log::channel('testing')->info('Log', ['Encabezado']);
                }
                if ($cant_nulos > 0 && $cant_nulos < 8) {
                    // Log::channel('testing')->info('Log', ['La fila es valida, se procede a almacenar']);
                    $this->filas_validas++;
                    ItemPagoProveedores::create([
                        'pago_proveedor_id' => $this->pago->id,
                        'proveedor' => $row[0],
                        'razon_social' => $row[1],
                        'tipo_documento' => $row[2],
                        'num_documento' => $row[3],
                        'fecha_emision' => $row[4],
                        'fecha_vencimiento' => $row[5],
                        'centro_costo' => $row[7],
                        'plazo' => $this->obtenerPlazo($row), 
                        'total' => $row[15],
                        'descripcion' => $row[16],
                        'valor_documento' => $row[17],
                        'retenciones' => $row[18],
                        'pagos' => $row[19],
                    ]);
                }
                // Log::channel('testing')->info('Log', ['cant_nulos', $cant_nulos, $this->filas_validas]);
            }
        }
    }

    private function obtenerPlazo(array $row)
    {
        //Calculo en que plazo es el pago
        for ($i = 9; $i <= 14; $i++) {
            if ($row[$i] !== null) {
                switch ($i) {
                    case 9: //por vencer
                        return ItemPagoProveedores::POR_VENCER;
                    case 10: // 30 días
                        return ItemPagoProveedores::TREINTA_DIAS;
                    case 11: //60 dias
                        return ItemPagoProveedores::SESENTA_DIAS;
                    case 12: //90 dias
                        return ItemPagoProveedores::NOVENTA_DIAS;
                    case 13: //120 dias
                        return ItemPagoProveedores::CIENTO_VEINTE_DIAS;
                    default: //mas de 120 dias
                        return ItemPagoProveedores::MAYOR_TIEMPO;
                }
                break;
            }
        }
    }
}
