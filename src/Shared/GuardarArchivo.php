<?php

namespace Src\Shared;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Src\Config\RutasStorage;


class GuardarArchivo
{
    private Model $modelo;
    private Request $request;
    private RutasStorage $ruta;

    public function __construct(Model $modelo, Request $request, RutasStorage $ruta)
    {
        $this->modelo = $modelo;
        $this->request = $request;
        $this->ruta = $ruta;
    }

    // Guarda un archivo a la vez
    public function execute()
    {
        $archivo = $this->request->file('file');

        $nombreArchivo = preg_replace('/[^\w\-\.]/', '', $archivo->getClientOriginalName());

        // $path = $archivo->storeAs($this->ruta->value, $archivo->getClientOriginalName());
        $path = $archivo->storeAs($this->ruta->value, $nombreArchivo);
        $ruta_relativa = Utils::obtenerRutaRelativaArchivo($path);
        return $this->modelo->archivos()->create([
            'nombre' => $archivo->getClientOriginalName(),
            'ruta' => $ruta_relativa,
            'tamanio_bytes' => filesize($archivo)
        ]);
    }

    /**
     * EN DESUSO 
     */
    public static function json(Request $request, RutasStorage $ruta, $carpeta_usuario = false,$empleado =null)
    {
        $empleado_identificacion = Empleado::where('id',$empleado)->first()->identificacion;
        $archivo = $request->file('file');
        $ruta_modificada = $ruta->value;
        if ($carpeta_usuario) {
            $ruta_modificada = 'public/' . $empleado_identificacion .'/' .$ruta->value;
        }
        $path = $archivo->store( $ruta_modificada);
        $ruta_relativa = Utils::obtenerRutaRelativaArchivo($path);
        $data = [
            'nombre' =>  $archivo->getClientOriginalName(),
            'ruta' => $ruta_relativa,
            'tamanio_bytes' => filesize($archivo)
        ];
        // Convierte el arreglo en una cadena JSON
        $json = json_encode($data);
        return $json;

    }
}
