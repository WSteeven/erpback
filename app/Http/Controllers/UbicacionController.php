<?php

namespace App\Http\Controllers;

use App\Models\Percha;
use App\Models\Piso;
use App\Models\Ubicacion;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => Ubicacion::all()]);
    }

    public function obtenerCodigoUbicacion($percha_id, $piso_id)
    {
        $percha = Percha::find($percha_id);
        $piso = Piso::find($piso_id);
        $codigo = $percha->nombre . $piso->fila . $piso->columna;

        return $codigo;
    }

    public function store(Request $request)
    {
        if ($request['piso_id'] && $request['percha_id']) {
            $request['codigo'] = $this->obtenerCodigoUbicacion($request->percha_id, $request->piso_id);
            $request->validate([
                'codigo' => 'required|string',
                'percha_id' => 'required|exists:perchas,id',
                'piso_id' => 'required|exists:pisos,id'
            ]);
        }else{
            $request->validate(['codigo' => 'required|string|unique:ubicaciones,codigo']);
        }

        $ubicacion = Ubicacion::create($request->all());

        return response()->json(['mensaje' => 'La ubicación ha sido creada con éxito', 'modelo' => $ubicacion]);
    }


    public function show(Ubicacion $ubicacion)
    {
        return response()->json(['modelo' => $ubicacion]);
    }


    public function update(Request $request, Ubicacion  $ubicacion)
    {
        $request->validate([
            'codigo' => 'string',
            'percha_id' => 'exists:perchas,id',
            'piso_id' => 'exists:pisos,id'
        ]);
        $ubicacion->update($request->all());

        return response()->json(['mensaje' => 'La ubicación ha sido actualizada con éxito', 'modelo' => $ubicacion]);
    }


    public function destroy(Ubicacion $ubicacion)
    {
        $ubicacion->delete();

        return response()->json(['mensaje' => 'La ubicación ha sido eliminada con éxito', 'modelo' => $ubicacion]);
    }
}
