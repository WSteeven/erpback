<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComprasProveedores\DetalleDepartamentoProveedorResource;
use App\Models\ComprasProveedores\DetalleDepartamentoProveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class DetalleDepartamentoProveedorController extends Controller
{
    private $entidad = 'Calificación';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $results = DetalleDepartamentoProveedorResource::collection(DetalleDepartamentoProveedor::filter()->get());
        return response()->json(compact('results'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida', $request->all()]);
        $detalle = DetalleDepartamentoProveedor::where('departamento_id', auth()->user()->empleado->departamento_id)->where('proveedor_id', $request->proveedor_id)->first();
        $detalle->update([
            'calificacion' => $request->calificacion,
            'empleado_id' => auth()->user()->empleado->id,
            'fecha_calificacion' => date("Y-m-d h:i:s"),
        ]);
        $modelo = $detalle->refresh();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetalleDepartamentoProveedor  $detalleDepartamentoProveedor
     * @return \Illuminate\Http\Response
     */
    public function show(DetalleDepartamentoProveedor $detalleDepartamentoProveedor)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetalleDepartamentoProveedor  $detalleDepartamentoProveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleDepartamentoProveedor $detalleDepartamentoProveedor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetalleDepartamentoProveedor  $detalleDepartamentoProveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleDepartamentoProveedor $detalleDepartamentoProveedor)
    {
        //
    }
}
