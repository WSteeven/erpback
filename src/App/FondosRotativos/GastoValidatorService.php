<?php

namespace Src\App\FondosRotativos;

use App\Http\Requests\GastoRequest;
use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\Gasto;
use Exception;
use Src\Config\PaisesOperaciones;
use Illuminate\Validation\Validator;
use Src\Shared\ValidarIdentificacion;

class GastoValidatorService
{
    private ?string $pais;

    public function __construct()
    {
        $this->pais = config('app.pais');
    }

    /**
     * @throws Exception
     */
    public function validarStore(Validator $validator, GastoRequest $request)
    {
        $this->validarNumeroComprobante($validator, $request);
        $this->validarRUC($validator, $request->ruc);
    }

    /**
     * @throws Exception
     */
    public function validarUpdate(Validator $validator, GastoRequest $request)
    {
        $gasto = Gasto::where('id', $request->id)->lockForUpdate()->first();
        if($gasto && $gasto->ruc !== $request->ruc) {
            $this->validarRUC($validator, $request->ruc);
        }
    }

    /**
     * @throws Exception
     */
    public function validarAprobacion(Validator $validator, GastoRequest $request)
    {
        $gasto = Gasto::where('id', $request->id)->lockForUpdate()->first();
        if(!$gasto) {
            $validator->errors()->add('id', 'Gasto no encontrado');
            return;
        }

        if ($gasto->estado === Gasto::APROBADO) {
            $validator->errors()->add('estado', 'El gasto ya fue aprobado');
        }
        if ($gasto->ruc !== $request->ruc) {
            $this->validarRUC($validator, $request->ruc);
        }
    }

    private function existeFactura(string $ruc, string $factura, string $estado): bool
    {
        return  Gasto::whereNotNull('factura')
            ->where('factura', '!=', '')
            ->where('ruc', $ruc)
            ->where('factura', $factura)
            ->where('estado', $estado)
            ->lockForUpdate()
            ->exists();
    }
    /**
     * @throws Exception
     */
    public function validarNumeroComprobante($validator, GastoRequest $request)
    {
        $ruc = $request->ruc;
        $factura = $request->factura;
        $numComprobante = $request->num_comprobante;

        // Validar si el número de factura ya está registrado
        $facturaExistente = $this->existeFactura($ruc, $factura, Gasto::APROBADO);

        if ($facturaExistente) {
            $validator->errors()->add('ruc', 'El número de factura ya se encuentra registrado');
        }

        $facturaPendiente = $this->existeFactura($ruc, $factura, Gasto::PENDIENTE);

        if ($facturaPendiente) {
            $validator->errors()->add('ruc', 'El número de factura ya se encuentra registrado');
        }

        //Validar si el número de comprobante ya está registrado
        $comprobante = Gasto::whereNotNull('num_comprobante')
            ->where('num_comprobante', $numComprobante)
            ->where('estado', Gasto::APROBADO)
            ->lockForUpdate()
            ->first();

        if ($comprobante) {
            $validator->errors()->add('num_comprobante', 'El número de comprobante ya se encuentra registrado');
        }

        $comprobantePendiente = Gasto::whereNotNull('num_comprobante')
            ->where('num_comprobante', $numComprobante)
            ->where('estado', Gasto::PENDIENTE)
            ->lockForUpdate()
            ->first();

        if ($comprobantePendiente) {
            $validator->errors()->add('num_comprobante', 'El número de comprobante ya se encuentra registrado');
        }

        // Validación adicional de longitud de factura para ciertos países
        if ($factura !== null) {
            switch ($this->pais) {
                case PaisesOperaciones::PERU:
                    break;
                default:
                    $longitudesPorDetalleViatico = [
                        [
                            "detalle" => DetalleViatico::PEAJE,
                            "cantidad" => 22,
                        ],
                        [
                            "detalle" => DetalleViatico::ENVIO_ENCOMIENDA,
                            "cantidad" => 17,
                        ],
                    ];

                    $index = array_search($request->detalle, array_column($longitudesPorDetalleViatico, 'detalle'));
                    $cantidadMinima = $index !== false && isset($longitudesPorDetalleViatico[$index]) ? $longitudesPorDetalleViatico[$index]['cantidad'] : 15;

                    $numFact = str_replace(' ', '', $factura);
                    $longitud = strlen($numFact);

                    if ($request->detalle == DetalleViatico::PEAJE) {
                        if ($longitud < max($cantidadMinima, 15)) {
                            throw new Exception('El número de dígitos en la factura es insuficiente. Por favor, ingrese al menos ' . max($cantidadMinima, 15) . ' dígitos en la factura.');
                        }
                    } else {
                        if ($longitud < $cantidadMinima) {
                            throw new Exception('El número de dígitos en la factura es insuficiente. Por favor, ingrese al menos ' . $cantidadMinima . ' dígitos en la factura.');
                        }
                    }
            }
        }
    }

    /**
     * @throws Exception
     */
    private function validarRUC(Validator $validator, string $ruc):void
    {
        if (substr_count($ruc, '9') < 9) {
            $validador = new ValidarIdentificacion();
            $esRucValido = $validador->validarRUCSRI($ruc);
            $esCedulaValida = $validador->validarCedula($ruc);
            if (!($esCedulaValida || $esRucValido)) {
                $validator->errors()->add('ruc', 'La identificación no pudo ser validada, revisa que sea una cédula/RUC válido');
            }
        }
    }

}
