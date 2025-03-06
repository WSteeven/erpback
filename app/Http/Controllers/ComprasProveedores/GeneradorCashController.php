<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Exports\CashGenericoExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\GeneradorCashRequest;
use App\Http\Resources\ComprasProveedores\GeneradorCashResource;
use App\Http\Resources\ComprasProveedores\PagoResource;
use App\Models\ComprasProveedores\GeneradorCash;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;
use Src\App\Sistema\PaginationService;
use Src\Shared\Utils;
use Maatwebsite\Excel\Facades\Excel;

class GeneradorCashController extends Controller
{
    private string $entidad = 'Cash';
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->paginationService = new PaginationService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request('export')) {
            $generador = GeneradorCash::ignoreRequest(['export', 'titulo'])->filter()->first();
            $results = $generador->pagos->sortByDesc('created_at') // Ordenar por fecha de creación, más reciente primero
                ->values()
                ->map(function ($pago, $index) {
                    $pagoResource = new PagoResource($pago);
                    $pagoResource = $pagoResource->resolve();
                    $pagoResource['num_secuencial'] = $index + 1;
                    $pagoResource['valor'] = $this->formatearValor($pagoResource['valor']);
                    $pagoResource['referencia_adicional'] = '|' . strtolower($pagoResource['referencia_adicional']);
                    unset($pagoResource['id']);
                    unset($pagoResource['generador_cash_id']);
                    unset($pagoResource['beneficiario_id']);
                    unset($pagoResource['cuenta_banco_id']);
                    return $pagoResource;
                });

            if (request('export') == 'xlsx') {
                $export = new CashGenericoExport($results, 'Cash');
                return Excel::download($export, 'cash.xlsx');
            }

            if (request('export') == 'txt') {
                // Generar el contenido del archivo de texto sin encabezados
                $fileContent = '';

                // Agregar los datos de los pagos sin incluir los encabezados
                foreach ($results as $pago) {
                    $fileContent .= implode("\t", array_values($pago)) . "\n";
                }

                // Crear la respuesta con el archivo para descarga
                return response($fileContent)
                    ->header('Content-Type', 'text/plain')
                    ->header('Content-Disposition', 'attachment; filename="cash.txt"');
            }
        }



        $results = GeneradorCash::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = GeneradorCashResource::collection($results);
        return response()->json(compact('results'));
    }

    private function formatearValor($value)
    {
        // Asegurar que el valor es numérico
        $val = str_replace([',', '.'], '', $value);

        // Formatear sin decimales y con 13 caracteres de longitud
        return str_pad($val, 13, '0', STR_PAD_LEFT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GeneradorCashRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();
            $datos['creador_id'] = Auth::user()->empleado->id;

            $modelo = GeneradorCash::create($datos);

            // Cuentas bancarias
            if ($request->has('pagos')) $modelo->pagos()->createMany($datos['pagos']);

            $modelo = new GeneradorCashResource($modelo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GeneradorCash $generador_cash)
    {
        $modelo = new GeneradorCashResource($generador_cash);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GeneradorCashRequest $request, GeneradorCash $generador_cash)
    {
        return DB::transaction(function () use ($request, $generador_cash) {
            $datos = $request->validated();

            // Actualizar el modelo principal
            $generador_cash->update($datos);

            // Obtener los IDs de pagos enviados en la solicitud
            $pagosEnviados = collect($datos['pagos'])->pluck('id')->filter()->toArray();

            // Eliminar pagos que no están en la solicitud
            $generador_cash->pagos()->whereNotIn('id', $pagosEnviados)->delete();

            // Recorrer los pagos enviados para actualizar o insertar
            foreach ($datos['pagos'] as $pagoData) {
                if (isset($pagoData['id'])) {
                    // Si el pago ya existe, actualizarlo
                    $pago = $generador_cash->pagos()->find($pagoData['id']);
                    if ($pago) {
                        $pago->update($pagoData);
                    }
                } else {
                    // Si el pago no tiene ID, crearlo
                    $generador_cash->pagos()->create($pagoData);
                }
            }

            // Refrescar el modelo y devolver respuesta
            $modelo = new GeneradorCashResource($generador_cash->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    public function duplicate($id)
    {
        $original = GeneradorCash::findOrFail($id);

        $duplicate = $original->replicate(); // Duplica el registro
        $duplicate->titulo = $original->titulo . ' (Copia)';
        $duplicate->save(); // Guarda el nuevo registro

        // Duplica los detalles asociados (hasMany)
        foreach ($original->pagos as $pago) {
            $nuevoDetalle = $pago->replicate();
            $nuevoDetalle->generador_cash_id = $duplicate->id; // Asignamos el nuevo ID
            $nuevoDetalle->save();
        }

        return response()->json([
            'mensaje' => 'Registro duplicado correctamente',
            'modelo' => new GeneradorCashResource($duplicate)
        ], 201);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
