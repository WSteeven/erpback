<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\BeneficiarioRequest;
use App\Http\Resources\ComprasProveedores\BeneficiarioResource;
use App\Models\ComprasProveedores\Beneficiario;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Src\App\Sistema\PaginationService;
use Src\Shared\Utils;
use Throwable;

class BeneficiarioController extends Controller
{
    private string $entidad = 'Beneficiario';
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
        $search = request('search');
        $paginate = request('paginate');

        if ($search) $query = Beneficiario::search($search);
        else $query = Beneficiario::ignoreRequest(['paginate'])->filter()->latest();

        if ($paginate) $results = $this->paginationService->paginate($query, 100, request('page'));
        else $results = $query->get();
        
        return BeneficiarioResource::collection($results);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BeneficiarioRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();
            $datos['creador_id'] = Auth::user()->empleado_id;

            $modelo = Beneficiario::create($datos);

            // Cuentas bancarias
            if ($request->has('cuentas_bancarias')) $modelo->cuentasBancarias()->createMany($datos['cuentas_bancarias']);

            $modelo = new BeneficiarioResource($modelo->refresh());
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
    public function show(Beneficiario $beneficiario)
    {
        $modelo = new BeneficiarioResource($beneficiario);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Beneficiario $beneficiario)
    {
        return DB::transaction(function () use ($request, $beneficiario) {
            $datos = $request->validated();
            $beneficiario->update($datos);
            $this->gestionarCuentasBancarias($request, $beneficiario);
            $modelo = new BeneficiarioResource($beneficiario->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        });
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

    private function gestionarCuentasBancarias(Request $request, Beneficiario $beneficiario)
    {
        // Obtener IDs de las cuentas enviadas en la solicitud
        $cuentasEnviadas = collect($request->input('cuentas_bancarias', []));

        // Obtener IDs de cuentas bancarias existentes en la base de datos
        $cuentasActuales = $beneficiario->cuentasBancarias()->pluck('id')->toArray();

        // Filtrar cuentas nuevas (las que no tienen ID)
        $nuevasCuentas = $cuentasEnviadas->whereNull('id')->all();

        // Filtrar cuentas que deben actualizarse (las que tienen ID y ya existen en la BD)
        $cuentasAActualizar = $cuentasEnviadas->whereIn('id', $cuentasActuales)->all();

        // Filtrar cuentas a eliminar (las que están en la BD pero no llegaron en la solicitud)
        $cuentasAEliminar = array_diff($cuentasActuales, $cuentasEnviadas->pluck('id')->filter()->toArray());

        // Agregar nuevas cuentas
        if (!empty($nuevasCuentas)) {
            $beneficiario->cuentasBancarias()->createMany($nuevasCuentas);
        }

        // Actualizar cuentas existentes
        foreach ($cuentasAActualizar as $cuenta) {
            $beneficiario->cuentasBancarias()->where('id', $cuenta['id'])->update([
                'tipo_cuenta' => $cuenta['tipo_cuenta'],
                'numero_cuenta' => $cuenta['numero_cuenta'],
                'banco_id' => $cuenta['banco'],
            ]);
        }

        // Eliminar cuentas que ya no están en la solicitud
        if (!empty($cuentasAEliminar)) {
            $beneficiario->cuentasBancarias()->whereIn('id', $cuentasAEliminar)->delete();
        }
    }
}
