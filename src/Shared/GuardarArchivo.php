<?php

namespace Src\Shared;

use App\Models\Carpeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
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

    public function execute()
    {
        $archivo = $this->request->file('file');
        // $carpeta = $this->request['carpeta'];
        // $carpeta = Carpeta::find($carpeta);

        $path = $archivo->store($this->ruta->value);
        $ruta_relativa = Utils::obtenerRutaRelativaArchivo($path);
        return $this->modelo->archivos()->create([
            'nombre' => $archivo->getClientOriginalName(),
            'ruta' => $ruta_relativa,
            'tamanio_bytes' => filesize($archivo)
        ]);


        //return response()->json(['mensaje' => 'Video actualizado exitosamente!']);
    }
    public static function json(Request $request, RutasStorage $ruta, $carpeta_usuario = false)
    {
        $archivo = $request->file('file');
        $ruta_modificada = $ruta->value;
        if ($carpeta_usuario) {
            $ruta_modificada = 'public/' . Auth::user()->empleado->identificacion .'/' .$ruta->value;
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
