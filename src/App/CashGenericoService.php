<?php

namespace Src\App;

use App\Models\ComprasProveedores\DatoBancarioProveedor;
use App\Models\RecursosHumanos\Banco;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class CashGenericoService
{

    public function __construct()
    {
    }

    public static function empaquetarDatosCash($datos)
    {
        try {
            $results = [];
            $row = [];
            foreach ($datos as $index => $dato) {
                $row['tipo'] = 'PA';
                $row['num_cuenta_empresa'] = '02653010903';
                $row['num_secuencial'] = intval($index) + 1;
                $row['num_comprobante'] = null;
                $row['codigo_beneficiario'] = $dato['identificacion_beneficiario']; //puede ser cedula o ruc del propietario de la cta
                $row['moneda'] = 'USD';
                $row['valor'] = str_replace(".", "", number_format($dato['total'], 2, ',', '.'));
                $row['forma_pago'] = 'CTA';
                $row['codigo_banco'] = Banco::obtenerDatosBanco($dato['banco'])->codigo;
                $row['tipo_cta'] = is_null($dato['tipo_cta']) ? Utils::obtenerTipoCta(DatoBancarioProveedor::AHORROS) : Utils::obtenerTipoCta($dato['tipo_cta']);
                $row['num_cuenta_beneficiario'] = $dato['num_cuenta_beneficiario'];
                $row['tipo_documento'] = strlen($row['codigo_beneficiario']) == 10 ? 'C' : 'R';
                $row['identificacion_beneficiario'] = $row['codigo_beneficiario'];
                $row['nombre_beneficiario'] = $dato['beneficiario'];
                $row['direccion'] = null;
                $row['ciudad'] = null;
                $row['telefono'] = null;
                $row['localidad'] = null;
                $row['referencia'] = $dato['referencia'];
                $row['referencia_adicional'] = $dato['correo'];
                
                $results[] = $row;
            }

            return $results;
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['error que se lanzara', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
}
