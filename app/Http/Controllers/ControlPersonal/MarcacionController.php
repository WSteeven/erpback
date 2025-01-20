<?php

namespace App\Http\Controllers\ControlPersonal;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RecursosHumanos\ControlPersonal\AsistenciaService;
use Src\Shared\Utils;

class MarcacionController extends Controller
{
    public AsistenciaService $service;

    public function __construct()
    {
        $this->service = new AsistenciaService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @throws ValidationException
     */
    public function sincronizarAsistencias()
    {
        try {
            $this->service->sincronizarAsistencias();
            return response()->json(['message' => 'Marcaciones sincronizadas correctamente.']);
        } catch (GuzzleException $e) {
            Log::channel('testing')->info('Log', ['GuzzleException en sincronizarAsistencias:', $e->getLine(), $e->getMessage()]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'GuzzleException -> sincronizarAsistencias');
        } catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e, 'sincronizarAsistencias');
        }
    }
}
