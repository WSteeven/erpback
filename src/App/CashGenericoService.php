<?php

namespace Src\App;

use App\Models\ComprasProveedores\DatoBancarioProveedor;
use App\Models\Empresa;
use App\Models\RecursosHumanos\Banco;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Src\Shared\ConeccionContifico;
use Src\Shared\Utils;

class CashGenericoService
{
    public function __construct()
    {
    }

    /**
     * La función "empacatarDatosCash" procesa y empaqueta datos para transacciones en efectivo en PHP,
     * manejando diversos detalles como información bancaria y detalles del beneficiario.
     * 
     * @param array $datos La función `empaquetarDatosCash` toma como parámetro un array de datos llamado
     * `datos`. Esta matriz contiene información sobre transacciones en efectivo, como detalles
     * bancarios, información del beneficiario y montos de la transacción.
     * 
     * @return array Se devuelve una matriz de filas de datos formateadas. Cada fila contiene información
     * relacionada con una transacción específica, como el tipo, números de cuenta, detalles del
     * beneficiario, moneda, monto del pago, detalles bancarios y referencias adicionales.
     */
    public static function empaquetarDatosCash($datos)
    {
        try {
            $results = [];
            $row = [];
            foreach ($datos as $index => $dato) {
                // Log::channel('testing')->info('Log', ['dato en empquetarDatosCash', $dato]);
                $banco = Banco::obtenerDatosBanco($dato['banco']);
                // Log::channel('testing')->info('Log', ['banco', $banco]);
                $row['tipo'] = 'PA';
                $row['num_cuenta_empresa'] = '02653010903';
                $row['num_secuencial'] = intval($index) + 1;
                $row['num_comprobante'] = null;
                $row['codigo_beneficiario'] = $dato['identificacion_beneficiario']; //puede ser cedula o ruc del propietario de la cta
                $row['moneda'] = 'USD';
                $row['valor'] = str_replace(".", "", number_format($dato['total'], 2, ',', '.'));
                $row['forma_pago'] = 'CTA';
                $row['codigo_banco'] = is_null($banco) ? null : $banco->codigo;
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

    /**
     * La función `obtenerDatosProveedor` recupera información sobre un proveedor en la api de `CONTIFICO`, 
     * como su número de identificación, tipo de cuenta y número de cuenta, en función de una entrada de texto
     * determinada.
     * 
     * @param ConeccionContifico $instancia Es una instancia de una clase que tiene un método `consultar` 
     * el cual se utiliza para recuperar datos relacionados a una persona o empresa que es proveedor. 
     * @param array $params el filtro por el cual se va a buscar determinado proveedor.
     * 
     * @return mixed Devuelve un array que contiene las siguientes claves:
     * - 'identificacion' 
     * - 'tipo_cta' 
     * - 'num_cta' 
     */
    public static function obtenerDatosProveedor($instancia, $params)
    {
        // aqui se obtendrá la cedula o ruc de la empresa o persona que es proveedor
        $objeto = [
            'identificacion' => '9999999999',
            'tipo_cta' => DatoBancarioProveedor::CORRIENTE,
            'num_cta' => '00000000000',
        ];
        try {
            $results = $instancia->consultar('persona', $params)->json();
            if (count($results) > 0) {
                // Log::channel('testing')->info('Log', ['results de consultar ruc', $results]);
                $objeto['tipo_cta'] = self::obtenerTipoCta($results[0]);
                $objeto['num_cta'] = is_null($results[0]['numero_tarjeta']) ? '00000000000' : $results[0]['numero_tarjeta'];
                if (strlen($results[0]['ruc'])) {
                    $objeto['identificacion'] = $results[0]['ruc'];
                } else {
                    $objeto['identificacion'] = $results[0]['cedula'];
                }
                return $objeto;
            } else {
                // Log::channel('testing')->info('Log', ['No se obtuvieron resultados', $results]);
                return $objeto;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Esta función PHP devuelve el tipo de cuenta bancaria según el valor de entrada.
     * 
     * @param array $item array que contiene la clave `tipo_cuenta`.
     * 
     * @return string Si el valor es 'A' devuelve `DatoBancarioProveedor::AHORROS`,
     * si el valor es 'C' devuelve `DatoBancarioProveedor::CORRIENTE`, y si no ninguno devuelve `null`
     */
    public static function obtenerTipoCta($item)
    {
        switch ($item['tipo_cuenta']) {
            case 'A':
                return DatoBancarioProveedor::AHORROS;
            case 'C':
                return DatoBancarioProveedor::CORRIENTE;
            default:
                return null;
        }
    }

    /**
     * La función `obtenerBanco` recupera información bancaria asociada a una empresa en función de su
     * número de identificación, priorizando a PRODUBANCO si está disponible.
     * 
     * @param string $identificacion El código proporcionado es una función PHP que tiene como objetivo
     * recuperar información bancaria en función de un número de identificación determinado. Primero
     * verifica si el número de identificación no es '9999999999', luego consulta la base de datos para
     * encontrar la empresa correspondiente según el número de identificación. Si se encuentra la
     * empresa, recupera el banco.
     * 
     * @return mixed La función `obtenerBanco` está devolviendo un array con dos elementos. El primer
     * elemento es el nombre del banco asociado con la identificación proporcionada y el segundo
     * elemento es el número de cuenta bancaria.
     */
    public static function obtenerBanco( $identificacion)
    {
        if ($identificacion !== '9999999999') {
            $empresa = Empresa::where('identificacion', $identificacion)->first();
            if ($empresa) {
                $datosBancarios = DatoBancarioProveedor::where('empresa_id', $empresa->id)->get();
                if ($datosBancarios) {
                    $datoProdubanco = $datosBancarios->first(function ($item) {
                        return isset($item->banco_id) && $item->banco_id == 1; // 1 es el primer registro de la tabla bancos y corresponde a PRODUBANCO
                    });
                    if ($datoProdubanco) {
                        //se devolverá la cta de produbanco asociada
                        return [$datoProdubanco->banco->nombre, $datoProdubanco->numero_cuenta];
                    } else {
                        return [$datosBancarios->first()->banco->nombre, $datosBancarios->first()->numero_cuenta];
                    }
                }
            }
        }
    }
}
