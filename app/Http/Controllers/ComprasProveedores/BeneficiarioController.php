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
use Log;
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
    public function update(BeneficiarioRequest $request, Beneficiario $beneficiario)
    {
        return DB::transaction(function () use ($request, $beneficiario) {
            $datos = $request->validated();
            $beneficiario->update($datos);
            $this->gestionarCuentasBancarias($datos, $beneficiario);
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

    private function gestionarCuentasBancarias($datos, Beneficiario $beneficiario)
    {
        // Obtener cuentas enviadas en la solicitud como colección
        $cuentasEnviadas = collect($datos['cuentas_bancarias']);

        // Obtener los IDs de las cuentas enviadas
        $idsCuentasEnviadas = $cuentasEnviadas->pluck('id')->filter()->toArray();

        // Eliminar las cuentas que no están en la solicitud
        $beneficiario->cuentasBancarias()->whereNotIn('id', $idsCuentasEnviadas)->delete();

        // Procesar actualización de cuentas existentes
        foreach ($cuentasEnviadas as $cuenta) {
            if (isset($cuenta['id'])) {
                // Si la cuenta ya existe, actualizarlo
                $cuentaEncontrada = $beneficiario->cuentasBancarias()->find($cuenta['id']);
                if ($cuentaEncontrada) {
                    $cuentaEncontrada->update($cuenta);
                }
            } else {
                // Si la cuenta no tiene ID, crearlo
                $beneficiario->cuentasBancarias()->create($cuenta);
            }
        }
    }
}
