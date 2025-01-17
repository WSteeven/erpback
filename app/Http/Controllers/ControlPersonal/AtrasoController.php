<?php

namespace App\Http\Controllers\ControlPersonal;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Src\App\RecursosHumanos\ControlPersonal\AtrasosService;
use Src\Shared\Utils;

class AtrasoController extends Controller
{
    public AtrasosService $service;

    public function __construct()
    {
        $this->service = new AtrasosService();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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
     * @throws ValidationException
     */
    public function sincronizarAtrasos()
    {
        try {
            $this->service->sincronizarAtrasos();
            return response()->json(['message' => 'Atrasos sincronizados correctamente.']);
        } catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e, 'sincronizarAtrasos');
        }
    }



}
