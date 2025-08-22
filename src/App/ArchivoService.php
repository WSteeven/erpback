<?php

namespace Src\App;

use Exception;
use Illuminate\Database\Eloquent\Collection;
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
     * @param Model $entidad El parámetro "entidad" es una instancia de la clase `Model`. Representa
     * una entidad o un objeto modelo que tiene una relación con la tabla `archivos`. Se supone que la
     * tabla `archivos` es una tabla relacionada con el modelo `entidad`, y la tabla `archivos`
     *
     * @return Collection archivos asociados con la entidad modelo dada.
     * @throws Exception
     */
    public static function listarArchivos(Model $entidad)
    {
//        Log::channel('testing')->info('Log', ['Dentro de request where has' => request('tipo')]);
        try {
            return $entidad->archivos()->filter()->get();
        } catch (Throwable $th) {
            Log::channel('testing')->info('Log', ['Error en el metodo listarArchivos de Archivo Service', $th->getMessage(), $th->getCode(), $th->getLine()]);
            throw new Exception($th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine(), $th->getCode());
        }
    }

    /**
     * La función guarda un archivo una vez en una ubicación de almacenamiento específica y crea una entrada
     * correspondiente en la base de datos.
     *
     * @param Model $entidad El parámetro "entidad" es una instancia de una clase modelo. Representa la
     * entidad u objeto con el que desea asociar el archivo cargado.
     * @param UploadedFile $archivo El parámetro "archivo" es una instancia de la clase UploadedFile,
     * que representa un archivo que ha sido subido a través de un formulario. Contiene información
     * sobre el archivo, como su nombre, tamaño y ubicación de almacenamiento temporal.
     * @param string $ruta El parámetro `` es una instancia de la clase `RutasStorage`, que
     * representa la ruta de almacenamiento donde se guardará el archivo. Contiene una propiedad
     * `value` que contiene el valor real de la ruta de almacenamiento.
     * @param string|null $tipo este campo se agrego luego de un tiempo por lo que sus valores fueron null
     * a partir de que se agrego con fecha 09/08/2024 este campo debe tener valor obligatoriamente asi exista
     * un único tipo para su modelo (MAYUSCULAS).
     *
     * @return Model el objeto modelo creado.
     * @throws Throwable
     */
    public static function guardarArchivo(Model $entidad, UploadedFile $archivo, string $ruta, string $tipo = null)
    {
        try {
            DB::beginTransaction();
            self::crearDirectorioConPermisos($ruta);
            $path = $archivo->store($ruta);
            $ruta_relativa = Utils::obtenerRutaRelativaArchivo($path);
            $modelo = $entidad->archivos()->create([
                'nombre' => $archivo->getClientOriginalName(),
                'ruta' => $ruta_relativa,
                'tamanio_bytes' => filesize($archivo),
                'tipo' => $tipo,
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
     * @param string $ruta El parámetro "ruta" es una cadena que representa la ruta del directorio que
     * se debe crear.
     */
    public static function crearDirectorioConPermisos(string $ruta)
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

    /**
     * Permite guardar un archivo con la opcion de eliminar el anterior.
     * Esto trabaja con el componente del front FileComponent.
     * @param UploadedFile $archivo El parámetro "archivo" es una instancia de la clase UploadedFile,
     * que representa un archivo que ha sido subido a través de un formulario. Contiene información
     * sobre el archivo, como su nombre, tamaño y ubicación de almacenamiento temporal.
     * @param string|null $nombre_archivo Nombre del archivo sin extension.
     * @param string $ruta El parámetro `` es una instancia de la clase `RutasStorage`, que
     * representa la ruta de almacenamiento donde se guardará el archivo. Contiene una propiedad
     * `value` que contiene el valor real de la ruta de almacenamiento.
     * @param string|null $archivo_anterior La ruta del archivo anterior para eliminarlo de la base de datos
     * @return string
     * @throws Exception
     */
    public static function guardarArchivoSingle(UploadedFile $archivo, string $ruta, string $nombre_archivo = null, string $archivo_anterior = null)
    {
        try {
            self::crearDirectorioConPermisos($ruta);

            // Si no se especifica un nombre, se usa el nombre original del archivo
            $extension = $archivo->getClientOriginalExtension();
            $nombre_archivo = $nombre_archivo ? $nombre_archivo . '.' . $extension : $archivo->getClientOriginalName();

            // Verificar si ya existe un archivo con ese nombre y evitar sobrescritura
            $fullPath = storage_path("app/$ruta/$nombre_archivo");
            if (file_exists($fullPath)) {
                $nombre_archivo = pathinfo($nombre_archivo, PATHINFO_FILENAME) . '_' . uniqid() . '.' . $extension;
            }

            $path = $archivo->storeAs($ruta, $nombre_archivo);

            // se elimina el archivo anterior del servidor para que no haya archivos obsoletos cuando se actualiza uno nuevo
            if ($archivo_anterior)
                if (file_exists(public_path($archivo_anterior)))
                    File::delete(public_path($archivo_anterior));

            return Utils::obtenerRutaRelativaArchivo($path);

        } catch (Throwable $th) {
            throw new Exception($th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine(), $th->getCode());
        }
    }
}
