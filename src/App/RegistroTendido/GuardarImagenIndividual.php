<?php

namespace Src\App\RegistroTendido;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Storage;
use Throwable;

class GuardarImagenIndividual
{
    private string $imagenBase64;
    private RutasStorage $ruta;
    private ?string $nombrePredeterminado;

    private ?string $imagenParaEliminar;

    public function __construct(string $imagen_base64, RutasStorage $ruta, string $ruta_a_eliminar = null, string $nombre_predeterminado = null)
    {
        $this->imagenBase64 = $imagen_base64;
        $this->ruta = $ruta;
        $this->imagenParaEliminar = $ruta_a_eliminar;
        $this->nombrePredeterminado = $nombre_predeterminado;
    }

    /**
     * @throws Throwable
     */
    public function execute()
    {
        try {
            //code...
            $imagen_decodificada = Utils::decodificarImagen($this->imagenBase64);
            $extension = Utils::obtenerExtension($this->imagenBase64);

            $directorio = $this->ruta->value;
            $nombre_archivo = $this->nombrePredeterminado ? $this->nombrePredeterminado . '.' . $extension : Utils::generarNombreArchivoAleatorio($extension);
            $ruta_relativa = Utils::obtenerRutaRelativaImagen($directorio, $nombre_archivo);
            $ruta_absoluta = Utils::obtenerRutaAbsolutaImagen($directorio, $nombre_archivo);

            // Verificar si el directorio existe y crearlo si no
            if (!Storage::exists($directorio)) {
                Storage::makeDirectory($directorio, 0755, true);
            }

            Image::make($imagen_decodificada)->resize(1800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($ruta_absoluta);

            // se elimina la imagen anterior del servidor para que no haya imagenes anteriores cuando se actualiza una nueva imagen
            if ($this->imagenParaEliminar)
                if (file_exists(public_path($this->imagenParaEliminar)))
                    File::delete(public_path($this->imagenParaEliminar));


            return $ruta_relativa;
        } catch (Throwable $th) {
            Log::channel('testing')->info('Log', ['TH', Utils::obtenerMensajeError($th, 'GuardarImagenIndividual')]);
            throw $th;
        }
    }
}
