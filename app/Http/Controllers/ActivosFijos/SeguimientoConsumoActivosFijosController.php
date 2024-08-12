<?php

namespace App\Http\Controllers\ActivosFijos;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivosFijos\SeguimientoConsumoActivosFijosRequest;
use App\Http\Resources\ActivosFijos\SeguimientoConsumoActivosFijosResource;
use App\Models\ActivosFijos\SeguimientoConsumoActivosFijos;
use Exception;
use Illuminate\Http\Request;
use Src\App\ActivosFijos\SeguimientoConsumoActivosFijosService;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class SeguimientoConsumoActivosFijosController extends Controller
{
    private SeguimientoConsumoActivosFijosService $seguimientoConsumoActivosFijosService;
    private string $entidad = 'Seguimiento';
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->seguimientoConsumoActivosFijosService = new SeguimientoConsumoActivosFijosService();
        $this->archivoService = new ArchivoService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* request()->validate([
            'detalle_producto_id' => 'required|numeric|integer|exists:detalles_productos,id',
            'cliente_id' => 'required|numeric|integer|exists:clientes,id',
            'resumen' => 'nullable|boolean',
            'seguimiento' => 'nullable|boolean',
        ]); */

        $results = $this->seguimientoConsumoActivosFijosService->seguimientoConsumoActivosFijos();
        $results = SeguimientoConsumoActivosFijosResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeguimientoConsumoActivosFijosRequest $request)
    {
        $validado = $request->validated();
        $seguimiento = SeguimientoConsumoActivosFijos::create($validado);

        // Descontar y sumar valores
        $this->seguimientoConsumoActivosFijosService->actualizarStockActivoFijoOcupado($request);

        $modelo = new SeguimientoConsumoActivosFijosResource($seguimiento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SeguimientoConsumoActivosFijosRequest $request, SeguimientoConsumoActivosFijos $seguimiento_consumo_activo_fijo)
    {
        $validado = $request->validated();

        $seguimiento_consumo_activo_fijo->cantidad_utilizada = $validado['cantidad_utilizada'];
        $seguimiento_consumo_activo_fijo->save();

        // if ($validado['justificativo_uso']) // Guardar archivo

        // Descontar y sumar valores
        $this->seguimientoConsumoActivosFijosService->actualizarStockActivoFijoOcupado($request);

        $modelo = new SeguimientoConsumoActivosFijosResource($seguimiento_consumo_activo_fijo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
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

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, SeguimientoConsumoActivosFijos $seguimiento_consumo_activo_fijo)
    {
        try {
            $results = $this->archivoService->listarArchivos($seguimiento_consumo_activo_fijo);
        } catch (\Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, SeguimientoConsumoActivosFijos $seguimiento_consumo_activo_fijo)
    {
        try {
            $modelo  = $this->archivoService->guardarArchivo($seguimiento_consumo_activo_fijo, $request->file, RutasStorage::SEGUIMIENTO_CONSUMO_ACTIVOS_FIJOS->value . '_' . $seguimiento_consumo_activo_fijo->id, SeguimientoConsumoActivosFijos::JUSTIFICATIVO_USO);
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            return $ex;
        }
        return response()->json(compact('mensaje', 'modelo'), 200);
    }
}
