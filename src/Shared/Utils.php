<?php

namespace Src\Shared;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Utils
{
    public static function esBase64(string $imagen): bool
    {
        return str_contains($imagen, ';base64');
    }

    public static function decodificarImagen(string $imagen_base64): string
    {
        $partes = explode(";base64,", $imagen_base64);
        return base64_decode($partes[1]);
    }

    public static function obtenerMimeType(string $imagen_base64): string
    {
        return explode("/", mime_content_type($imagen_base64))[1];
    }

    public static function obtenerExtension(string $imagen_base64): string
    {
        $mime_type = self::obtenerMimeType($imagen_base64);
        return explode("+", $mime_type)[0];
    }

    public static function arrayToCsv(string $campos, array $listado): string
    {
        $ruta_archivo_temporal = '../storage/app/plantilla.csv';
        $archivo_csv = fopen($ruta_archivo_temporal, 'w'); // default public
        fputs($archivo_csv, $campos . PHP_EOL);

        foreach ($listado as $fila) {
            fputs($archivo_csv, implode(',', $fila) . PHP_EOL);
        }
        fclose($archivo_csv);
        return $ruta_archivo_temporal;
    }

    public static function generarNombreArchivoAleatorio(string $extension): string
    {
        $nombre = Str::random(10);
        return $nombre . '.' . $extension;
    }

    public static function obtenerRutaAbsolutaImagen(string $ruta_imagen_en_public, string $nombre_archivo): string
    {
        return storage_path() . '/app/' . $ruta_imagen_en_public . '/' . $nombre_archivo; // aqui cambie
    }

    public static function obtenerRutaRelativaImagen(string $ruta, string $nombre_archivo = ""): string
    {
        $ruta = str_replace('public/', '', $ruta);
        return '/storage/' . $ruta . '/' . $nombre_archivo;
    }

    public static function obtenerRutaRelativaArchivo(string $ruta): string
    {
        $ruta = str_replace('public/', '', $ruta);
        return '/storage/' . $ruta;
    }

    public static function obtenerMensaje(string $entidad, string $metodo, string $genero = 'M')
    {
        $mensajes = [
            'store' => $entidad . ' guardad' . ($genero == 'M' ? 'o' : 'a') . ' exitosamente!',
            'update' => $entidad . ' actualizad' . ($genero == 'M' ? 'o' : 'a') . ' exitosamente!',
            'destroy' => $entidad . ' eliminad' . ($genero == 'M' ? 'o' : 'a') . ' exitosamente!',
        ];

        return $mensajes[$metodo];
    }

    /**
     * Metodo para generar codigos de N dígitos basandose en el id recibido
     * @param int $id
     * @param int $longitud
     * @return String $codigo  de N dígitos
     */
    public static function generarCodigoConLongitud(int $id, int $longitud)
    {
        $codigo = "";
        while (strlen($codigo) < ($longitud - strlen($id))) {
            $codigo .= "0";
        }
        $codigo .= strval($id);
        return $codigo;
    }

    /**
     * Función para validar una dirección de correo.
     * Esta función solo comprueba que la dirección de correo tenga la estructura <identificador@dominio.com/ec/org, etc>.
     * Para una validación más completa se debe usar expresiones regulares. 
     * 
     */
    public static function validarEmail(String $email)
    { //Aún no está probada
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    public static function quitarEspaciosComasString(string $cadena)
    {
        return str_replace(', ', '', $cadena);
    }

    public static function convertirStringComasArray(string $cadena)
    {
        return explode(',', $cadena);
    }

    public static function tiempoTranscurrido($min, $type)
    { //obtener segundos
        $sec = $min * 60;
        //dias es la division de n segs entre 86400 segundos que representa un dia
        $dias = floor($sec / 86400);
        //mod_hora es el sobrante, en horas, de la division de días; 
        $mod_hora = $sec % 86400;
        //hora es la division entre el sobrante de horas y 3600 segundos que representa una hora;
        $horas = floor($mod_hora / 3600);
        //mod_minuto es el sobrante, en minutos, de la division de horas; 
        $mod_minuto = $mod_hora % 3600;
        //minuto es la division entre el sobrante y 60 segundos que representa un minuto;
        $minutos = floor($mod_minuto / 60);
        if ($minutos <= 0) {
            $text =  $min . " segundoxxx(s)";
        } elseif ($horas <= 0) {
            $text = $minutos . ' minuto(s)';
        } elseif ($dias <= 0) {
            if ($type == 'round')
            //nos apoyamos de la variable type para especificar si se muestra solo las horas
            {
                $text = $horas . ' hora(s)';
            } else {
                $text = $horas . " hora(s) y " . $minutos . ' minuto(s)dd';
            }
        } else {
            //nos apoyamos de la variable type para especificar si se muestra solo los dias
            if ($type == 'round') {
                $text = $dias . ' dia(s)';
            } else {
                $text = $dias . " dia(s) - " . $horas . " hora(s) y " . $minutos . " minuto(s) y " . $sec . " segundo(s)";
            }
        }
        return $text;
    }

    public static function tiempoTranscurridoSeconds($sec, $type)
    { //obtener segundos
        // $sec = $min * 60;
        //dias es la division de n segs entre 86400 segundos que representa un dia
        $dias = floor($sec / 86400);
        //mod_hora es el sobrante, en horas, de la division de días; 
        $mod_hora = $sec % 86400;
        //hora es la division entre el sobrante de horas y 3600 segundos que representa una hora;
        $horas = floor($mod_hora / 3600);
        //mod_minuto es el sobrante, en minutos, de la division de horas; 
        $mod_minuto = $mod_hora % 3600;
        //minuto es la division entre el sobrante y 60 segundos que representa un minuto;
        $minutos = floor($mod_minuto / 60);
        if ($minutos <= 0) {
            $text =  $sec . " segundoxxx(s)";
        } elseif ($horas <= 0) {
            $text = $minutos . ' minuto(s)';
        } elseif ($dias <= 0) {
            if ($type == 'round')
            //nos apoyamos de la variable type para especificar si se muestra solo las horas
            {
                $text = $horas . ' hora(s)';
            } else {
                $text = $horas . " hora(s) y " . $minutos . ' minuto(s)dd';
            }
        } else {
            //nos apoyamos de la variable type para especificar si se muestra solo los dias
            if ($type == 'round') {
                $text = $dias . ' dia(s)';
            } else {
                $text = $dias . " dia(s) - " . $horas . " hora(s) y " . $minutos . " minuto(s) y " . $sec . " segundo(s)";
            }
        }
        return $text;
    }
}
