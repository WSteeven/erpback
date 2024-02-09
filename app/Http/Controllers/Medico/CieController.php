<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Resources\Medico\CieResource;
use App\Imports\Medico\CieImport;
use App\Models\Medico\Cie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use League\Csv\Reader;
use Maatwebsite\Excel\Excel as ExcelUtil;
use Src\Shared\Utils;
use Maatwebsite\Excel\Facades\Excel;

class CieController extends Controller
{
    private $entidad = 'CIE';

    public function __construct()
    {
        $this->middleware('can:puede.ver.cies')->only('index', 'show');
        $this->middleware('can:puede.crear.cies')->only('store');
        $this->middleware('can:puede.editar.cies')->only('update');
        $this->middleware('can:puede.eliminar.cies')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = Cie::ignoreRequest(['campos'])->filter()->get();
        $results = CieResource::collection($results);
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
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $cie = Cie::create($datos);
            $modelo = new CieResource($cie);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de cie' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Cie  $cie
     * @return \Illuminate\Http\Response
     */
    public function show($cie)
    {
        $modelo = new CieResource($cie);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Cie  $cie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cie)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $cie->update($datos);
            $modelo = new CieResource($cie->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de cie' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function archivoCie(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                throw ValidationException::withMessages([
                    'file' => ['Debe seleccionar al menos un archivo.'],
                ]);
            }
            Excel::import(new CieImport(), $request->file);
            return response()->json(['mensaje' => 'Subido exitosamente!']);
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'file' => [$e->getMessage(), $e->getLine()],
            ]);

            Log::channel('testing')->info('Log', ['ERROR en el insert de permiso de prestamo hipotecario', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  Cie  $cie
     * @return \Illuminate\Http\Response
     */
    public function destroy($cie)
    {
        try {
            DB::beginTransaction();
            $cie->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de cie' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
