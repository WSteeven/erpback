<?php

namespace Src\App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class ArchivoService
{

    public function __construct()
    {
    }

    /**
     * La función "listarArchivos" recupera una lista de archivos asociados con una entidad modelo
     * determinada en PHP.
     *
     * @param Model entidad El parámetro "entidad" es una instancia de la clase `Model`. Representa
     * una entidad o un objeto modelo que tiene una relación con la tabla `archivos`. Se supone que la
     * tabla `archivos` es una tabla relacionada con el modelo `entidad`, y la tabla `archivos`
     *
     * @return results archivos asociados con la entidad modelo dada.
     */
    public static function listarArchivos(Model $entidad)
    {
        $results = [];
        try {
            $results =  $entidad->archivos()->get();
            return $results;
        } catch (Throwable $th) {
            Log::channel('testing')->info('Log', ['Error en el metodo listarArchivos de Archivo Service', $th->getMessage(), $th->getCode(), $th->getLine()]);
            throw new Exception($th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine(), $th->getCode());
        }
    }

    /**
     * La función guarda un archivo una vez en una ubicación de almacenamiento específica y crea una entrada
     * correspondiente en la base de datos.
     *
     * @param Model entidad El parámetro "entidad" es una instancia de una clase modelo. Representa la
     * entidad u objeto con el que desea asociar el archivo cargado.
     * @param UploadedFile archivo El parámetro "archivo" es una instancia de la clase UploadedFile,
     * que representa un archivo que ha sido subido a través de un formulario. Contiene información
     * sobre el archivo, como su nombre, tamaño y ubicación de almacenamiento temporal.
     * @param RutasStorage|string ruta El parámetro `` es una instancia de la clase `RutasStorage`, que
     * representa la ruta de almacenamiento donde se guardará el archivo. Contiene una propiedad
     * `value` que contiene el valor real de la ruta de almacenamiento.
     *
     * @return el objeto modelo creado.
     */
    public static function guardarArchivo(Model $entidad, UploadedFile $archivo, string $ruta)
    {
        try {
            DB::beginTransaction();
            self::crearDirectorioConPermisos($ruta);
            $path = $archivo->store($ruta);
            $ruta_relativa = Utils::obtenerRutaRelativaArchivo($path);
            $modelo =  $entidad->archivos()->create([
                'nombre' => $archivo->getClientOriginalName(),
                'ruta' => $ruta_relativa,
                'tamanio_bytes' => filesize($archivo),
            ]);
            DB::commit();
            // Log::channel('testing')->info('Log', ['Archivo nuevo creado en Archivo Service', $modelo]);
            return $modelo;
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error en el guardar de Archivo Service', $th->getMessage(), $th->getCode(), $th->getLine()]);
            throw new Exception($th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine(), $th->getCode());
        }
    }


    /**
     * La función crea un directorio con permisos en PHP usando la clase Storage del framework Laravel.
     *
     * @param string ruta El parámetro "ruta" es una cadena que representa la ruta del directorio que
     * se debe crear.
     */
    private static function crearDirectorioConPermisos(string $ruta)
    {
        try {
            if (!Storage::exists($ruta)) {
                // Storage::makeDirectory($ruta, 0755, true); //esta linea en el servidor crea con 0700 en lugar de 0755, probaremos con mkdir  
                // mkdir($ruta, 0755, true); // mkdir tampoco funcionó, se prueba con otro metodo
                // Storage::disk('local')->mkdir($ruta,0755,true);
                Storage::disk('local')->makeDirectory($ruta, 0755);
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Erorr al crear el directorio:', $e->getMessage()]);
        }
    }
}
