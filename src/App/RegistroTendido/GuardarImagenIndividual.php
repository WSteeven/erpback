<?php

namespace Src\App\RegistroTendido;

use Src\Config\RutasStorage;
use Intervention\Image\Facades\Image;
use Src\Shared\Utils;

class GuardarImagenIndividual
{
    private String $imagen_base64;
    private RutasStorage $ruta;
    private String $nombre_predeterminado;

    public function __construct(string $imagen_base64, RutasStorage $ruta, string $nombre_predeterminado = null)
    {
        $this->imagen_base64 = $imagen_base64;
        $this->ruta = $ruta;
        $this->nombre_predeterminado = $nombre_predeterminado;
    }

    public function execute()
    {
        $imagen_decodificada = Utils::decodificarImagen($this->imagen_base64);
        $extension = Utils::obtenerExtension($this->imagen_base64);

        $directorio = $this->ruta->value;
        $nombre_archivo = $this->nombre_predeterminado . '.' . $extension ?? Utils::generarNombreArchivoAleatorio($extension);
        $ruta_relativa = Utils::obtenerRutaRelativaImagen($directorio, $nombre_archivo);
        $ruta_absoluta = Utils::obtenerRutaAbsolutaImagen($directorio, $nombre_archivo);

        Image::make($imagen_decodificada)->resize(1600, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($ruta_absoluta);

        return $ruta_relativa;
    }
}
