<?php

namespace Src\App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class ArchivoService
{

    public function __construct()
    {
    }

    /**
     * La función guarda un archivo en una ubicación de almacenamiento específica y crea una entrada
     * correspondiente en la base de datos.
     * 
     * @param Model entidad El parámetro "entidad" es una instancia de una clase modelo. Representa la
     * entidad u objeto con el que desea asociar el archivo cargado.
     * @param UploadedFile archivo El parámetro "archivo" es una instancia de la clase UploadedFile,
     * que representa un archivo que ha sido subido a través de un formulario. Contiene información
     * sobre el archivo, como su nombre, tamaño y ubicación de almacenamiento temporal.
     * @param RutasStorage ruta El parámetro `` es una instancia de la clase `RutasStorage`, que
     * representa la ruta de almacenamiento donde se guardará el archivo. Contiene una propiedad
     * `value` que contiene el valor real de la ruta de almacenamiento.
     * 
     * @return el objeto modelo creado.
     */
    public static function guardar(Model $entidad, UploadedFile $archivo, RutasStorage $ruta)
    {
        try {
            DB::beginTransaction();

            $path = $archivo->store($ruta->value);
            $ruta_relativa = Utils::obtenerRutaRelativaArchivo($path);
            $modelo =  $entidad->archivos()->create([
                'nombre' => $archivo->getClientOriginalName(),
                'ruta' => $ruta_relativa,
                'tamanio_bytes' => filesize($archivo),
            ]);
            DB::commit();
            return $modelo;
        } catch (Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine(), $th->getCode());
        }
    }
}
