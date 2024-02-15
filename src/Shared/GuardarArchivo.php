<?php

namespace Src\Shared;

use App\Models\Carpeta;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Src\Config\RutasStorage;

use function React\Promise\Stream\first;

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

        $path = $archivo->storeAs($this->ruta->value, $archivo->getClientOriginalName());
        $ruta_relativa = Utils::obtenerRutaRelativaArchivo($path);
        return $this->modelo->archivos()->create([
            'nombre' => $archivo->getClientOriginalName(),
            'ruta' => $ruta_relativa,
            'tamanio_bytes' => filesize($archivo)
        ]);


        //return response()->json(['mensaje' => 'Video actualizado exitosamente!']);
    }
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
