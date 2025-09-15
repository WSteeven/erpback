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

        if (!$gasto) {
            $validator->errors()->add('id', 'Gasto no encontrado');
            return;
        }

        if ($gasto->ruc !== $request->ruc) {
            $this->validarRUC($validator, $request->ruc);
        }

        // Reusar la misma validación de comprobantes, pero ignorando este gasto
        $this->validarNumeroComprobante($validator, $request, $gasto->id);
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

    private function existeFactura(string $ruc, ?string $factura, string $estado, ?int $ignoreId = null): bool
    {
        $query = Gasto::whereNotNull('factura')
            ->where('factura', '!=', '')
            ->where('ruc', $ruc)
            ->where('factura', $factura)
            ->where('estado', $estado)
            ->lockForUpdate();

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    /**
     * @throws Exception
     */
    public function validarNumeroComprobante($validator, GastoRequest $request, ?int $ignoreId = null)
    {
        $ruc = $request->ruc;
        $factura = $request->factura;
        $numComprobante = $request->num_comprobante;

        // Validar duplicado de factura (APROBADO o PENDIENTE)
        if ($factura !== null) {
            $facturaDuplicada = $this->existeFactura($ruc, $factura, Gasto::APROBADO, $ignoreId)
                || $this->existeFactura($ruc, $factura, Gasto::PENDIENTE, $ignoreId);

            if ($facturaDuplicada) {
                $validator->errors()->add('factura', 'El número de factura ya se encuentra registrado');
            }
        }

        // Validar duplicado de comprobante (APROBADO o PENDIENTE)
        if (!empty($numComprobante)) {
            $comprobanteDuplicado = Gasto::whereNotNull('num_comprobante')
                ->where('num_comprobante', $numComprobante)
                ->whereIn('estado', [Gasto::APROBADO, Gasto::PENDIENTE])
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->lockForUpdate()
                ->exists();

            if ($comprobanteDuplicado) {
                $validator->errors()->add('num_comprobante', 'El número de comprobante ya se encuentra registrado');
            }
        }

        // Validación adicional de longitud de factura
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
