<?php

namespace App\Http\Controllers;

use App\Models\BitacoraVehicular;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Http\Request;

class BitacoraVehicularController extends Controller
{
    private $entidad = 'Bitacora Vehicular';
    public function __construct()
    {
        $this->middleware('can:puede.ver.bitacoras_vehiculos')->only('index', 'show');
        $this->middleware('can:puede.crear.bitacoras_vehiculos')->only('store');
        $this->middleware('can:puede.editar.bitacoras_vehiculos')->only('update');
        $this->middleware('can:puede.eliminar.bitacoras_vehiculos')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole(User::ROL_ADMINISTRADOR_VEHICULOS))
            $results = BitacoraVehicular::all();
        else{
            $empleado = Empleado::where('usuario_id', auth()->user()->id)->first();
            $results = $empleado->bitacoras();
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BitacoraVehicular  $bitacoraVehicular
     * @return \Illuminate\Http\Response
     */
    public function show(BitacoraVehicular $bitacoraVehicular)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BitacoraVehicular  $bitacoraVehicular
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BitacoraVehicular $bitacoraVehicular)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BitacoraVehicular  $bitacoraVehicular
     * @return \Illuminate\Http\Response
     */
    public function destroy(BitacoraVehicular $bitacoraVehicular)
    {
        //
    }
}
