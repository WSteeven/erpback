<?php

namespace Src\Shared;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Integer;

class Utils
{
    public static $meses = array(
        "January" => "Enero",
        "February" => "Febrero",
        "March" => "Marzo",
        "April" => "Abril",
        "May" => "Mayo",
        "June" => "Junio",
        "July" => "Julio",
        "August" => "Agosto",
        "September" => "Septiembre",
        "October" => "Octubre",
        "November" => "Noviembre",
        "December" => "Diciembre"
    );
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

    /**
     * La función `eliminarArchivoServidor` se utiliza para eliminar un archivo o imagen del servidor,
     * con una opción para reemplazar la ruta del archivo si es necesario.
     *
     * @param string $url La URL del archivo de imagen que se eliminará del servidor.
     * @param bool $reemplazar El parámetro "reemplazar" es un valor booleano que determina si la "url" debe
     * reemplazarse con una versión modificada antes de eliminar la imagen del servidor. Si se
     * establece en verdadero, la "url" se modifica reemplazando la palabra "storage" por
     * "public" antes de eliminar el archivo del servidor
     * @return void
     */
    public static function eliminarArchivoServidor($url, $reemplazar = true)
    {
        if ($reemplazar) {
            $ruta = str_replace('storage', 'public', $url);
            Storage::delete($ruta);
        } else Storage::delete($url);
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

    public static function validarNumeroCuenta($numeroCuenta)
    {
        if (strlen($numeroCuenta) != 11) {
            return false;
        }

        $codigoBanco = substr($numeroCuenta, 0, 4);
        $numeroCuenta = substr($numeroCuenta, 4, 6);
        $digitoControl = intval(substr($numeroCuenta, -1));

        // Validar la lógica del dígito de control (por ejemplo, suma de ciertos dígitos)
        $sumaDigitos = array_sum(str_split($numeroCuenta));
        Log::channel('testing')->info('Log', ['key', $numeroCuenta, $digitoControl, $sumaDigitos]);

        if ($sumaDigitos % 10 == $digitoControl) {
            return true;
        } else {
            return false;
        }
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

    /**
     * La función "mayusc" en PHP convierte una cadena a mayúsculas.
     *
     * @param string $value El parámetro de valor es una cadena que desea convertir a mayúsculas.
     *
     * @return String el valor de entrada convertido a mayúsculas.
     */
    public static function mayusc($value)
    {
        return strtoupper($value);
    }

    /**
     * La función "obtenerNumeroEnCadena" toma una cadena como entrada en formato "7 dias", "15 dias", "30 dias", etc.
     * y devuelve el primer valor numérico encontrado en la cadena.
     *
     * @param string $cadena El parámetro "cadena" es una cadena que contiene dos palabras o números
     * separados por espacios.
     *
     * @return int un valor entero.
     */
    public static function obtenerNumeroEnCadena(string $cadena)
    {
        $partes = explode(" ", $cadena);
        if (count($partes) > 0 && is_numeric($partes[0])) return intval($partes[0]);
        return -1;
    }


    /**
     * La función "obtenerDiasRestantes" calcula el número de días que faltan entre una fecha
     * determinada y la fecha actual más un número de días especificado.
     *
     * @param DateTime $fecha El parámetro  es un objeto DateTime que representa la fecha de
     * inicio.
     * @param int diasAsumar El parámetro "diasAsumar" es un número entero que representa el número de
     * días a sumar a la fecha dada.
     *
     * @return int el número de días que quedan entre la fecha actual y la suma de las fechas dadas
     */
    public static function obtenerDiasRestantes(DateTime $fecha, int $diasAsumar)
    {
        $nuevaFecha = $fecha->addDays($diasAsumar);

        $diferencia = Carbon::now()->diffInDays($nuevaFecha, false);
        return $diferencia;
    }

    public static function convertArrayToString($array, $separator)
    {
        // Log::channel('testing')->info('Log', ['Array recibido', $array, 'separator', $separator]);
        if (is_array($array) && count($array) > 0) {
            // Log::channel('testing')->info('Log', ['Array transformado',implode($separator, $array)]);
            return implode($separator, $array);
        } else {
            if (empty($array)) {
                return null;
            }
            return $array;
        }
    }

    public static function colorDefault()
    {
        return [
            '#dce83a',
            '#13d664',
            '#d61324',
            '#4854d4',
            '#a8a0a2',
            '#7d6351',
            '#e6e6e6'
        ];
    }
    public static function coloresBlueTeal()
    {
        return [
            "#348888",
            "#22BABB",
            "#9EF8EE",
            "#FA7F08",
            "#F24405",
            "orange",
            "cyan",
            "magenta",
            "gray",
        ];
    }
    public static function coloresAzulPrincipal()
    {
        return [
            "#0000ff",
            "#add8e6",
            "#00ffff",
            "#00bfff",
            "#40e0d0",
            "#008080",
            "#4b0082",
            "#800080",
            "#ee82ee",
        ];
    }
    public static function coloresAmarilloPrincipal()
    {
        return [
            "#ffff00",
            "#ffeb3b",
            "#ffffe0",
            "#ffdab9",
            "#bdb76b",
            "#8b8b00",
            "#cd9b33",
            "#e59b33",
            "#f0e68c",
        ];
    }
    public static function coloresVintagePrincipal()
    {
        return [
            "#d8bfd8",
            "#e6e6fa",
            "#f0e68c",
            "#96a8a2",
            "#8b8b00",
            "#bdb76b",
            "#f5f5dc",
            "#999999",
            "#696969",
        ];
    }
    public static function coloresAqua()
    {
        return [
            "#00ffff",
            "#adff2f",
            "#a0d6ff",
            "#40e0d0",
            "#008080",
            "#8080ff",
            "#96c8ff",
            "#b0e0e6",
            "#ffffff",
        ];
    }

    public static function coloresTemaPrimaveral()
    {
        return [
            "#fff8dc",
            "#d9f0d3",
            "#d0e0e0",
            "#b2dfdb",
            "#aed581",
            "#8ae234",
            "#66cdaa",
            "#41b6c4",
            "#29a7d7",
        ];
    }

    public static function coloresTemaOtonio()
    {
        return [
            "#f7d000",
            "#f4a460",
            "#d78b00",
            "#c57100",
            "#8b4513",
            "#a0522d",
            "#7f3a1f",
            "#6c341e",
            "#4b2b1c",
        ];
    }

    public function coloresTemaVerano()
    {
        return [
            "#fff5f0",
            "#d3e0e9",
            "#a0c4de",
            "#76a5cf",
            "#4f86bc",
            "#3b6eac",
            "#2d59a5",
            "#234f9d",
            "#1a4795",
        ];
    }
    public function coloresTemaMapaCalor()
    {
        return [
            "#ff0000",
            "#ff7f00",
            "#ffb74d",
            "#ffec8b",
            "#ffff00",
            "#bfff00",
            "#9eff00",
            "#8eff00",
            "#7eff00",
        ];
    }
    public function coloresTemaMapaFrio()
    {
        return [
            "#0000ff",
            "#00a0ff",
            "#00dfff",
            "#00ffff",
            "#87ffff",
            "#dfffdf",
            "#ffffff",
            "#dfffff",
            "#ffff87",
        ];
    }
    public static function coloresAleatorios()
    {
        return [
            "#3498db", // Azul
            "#e74c3c", // Rojo
            "#2ecc71", // Verde
            "#f39c12", // Naranja
            "#9b59b6", // Morado
            "#1abc9c", // Turquesa
            "#e67e22", // Zanahoria
            "#34495e", // Gris Azulado
            "#d35400", // Calabaza
            "#27ae60"  // Esmeralda
        ];
    }
    public static function coloresAleatorios2()
    {
        return [
            "#ff0000",
            "#00ff00",
            "#0000ff",
            "#ffff00",
            "#ffa500",
            "#ff00ff",
            "#ff7f00",
            "#00ffff",
            "#ffffff",
        ];
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES (tomadas del codigo de Yefraina)
     * ______________________________________________________________________________________
     */

    private static function unidad($numero)
    {
        switch ($numero) {
            case 9:
                return "NUEVE";
            case 8:
                return "OCHO";
            case 7:
                return "SIETE";
            case 6:
                return "SEIS";
            case 5:
                return "CINCO";
            case 4:
                return "CUATRO";
            case 3:
                return "TRES";
            case 2:
                return "DOS";
            case 1:
                return "UNO";
            case 0:
                return "";
        }
    }

    private static function decena($numero)
    {

        if ($numero >= 90 && $numero <= 99) {
            $numd = "NOVENTA ";
            if ($numero > 90)
                $numd = $numd . "Y " . (self::unidad($numero - 90));
        } else if ($numero >= 80 && $numero <= 89) {
            $numd = "OCHENTA ";
            if ($numero > 80)
                $numd = $numd . "Y " . (self::unidad($numero - 80));
        } else if ($numero >= 70 && $numero <= 79) {
            $numd = "SETENTA ";
            if ($numero > 70)
                $numd = $numd . "Y " . (self::unidad($numero - 70));
        } else if ($numero >= 60 && $numero <= 69) {
            $numd = "SESENTA ";
            if ($numero > 60)
                $numd = $numd . "Y " . (self::unidad($numero - 60));
        } else if ($numero >= 50 && $numero <= 59) {
            $numd = "CINCUENTA ";
            if ($numero > 50)
                $numd = $numd . "Y " . (self::unidad($numero - 50));
        } else if ($numero >= 40 && $numero <= 49) {
            $numd = "CUARENTA ";
            if ($numero > 40)
                $numd = $numd . "Y " . (self::unidad($numero - 40));
        } else if ($numero >= 30 && $numero <= 39) {
            $numd = "TREINTA ";
            if ($numero > 30)
                $numd = $numd . "Y " . (self::unidad($numero - 30));
        } else if ($numero >= 20 && $numero <= 29) {
            if ($numero == 20)
                $numd = "VEINTE ";
            else
                $numd = "VEINTI" . (self::unidad($numero - 20));
        } else if ($numero >= 10 && $numero <= 19) {
            switch ($numero) {
                case 10:
                    return "DIEZ ";
                case 11:
                    return  "ONCE ";
                case 12:
                    return  "DOCE ";
                case 13:
                    return "TRECE ";
                case 14:
                    return  "CATORCE ";
                case 15:
                    return "QUINCE ";
                case 16:
                    return  "DIECISEIS ";
                case 17:
                    return  "DIECISIETE ";
                case 18:
                    return "DIECIOCHO ";
                case 19:
                    return  "DIECINUEVE ";
            }
        } else
            $numd = self::unidad($numero);
        return $numd;
    }

    private static function centena($numc)
    {
        if ($numc >= 100) {
            if ($numc >= 900 && $numc <= 999) {
                $numce = "NOVECIENTOS ";
                if ($numc > 900)
                    $numce = $numce . (self::decena($numc - 900));
            } else if ($numc >= 800 && $numc <= 899) {
                $numce = "OCHOCIENTOS ";
                if ($numc > 800)
                    $numce = $numce . (self::decena($numc - 800));
            } else if ($numc >= 700 && $numc <= 799) {
                $numce = "SETECIENTOS ";
                if ($numc > 700)
                    $numce = $numce . (self::decena($numc - 700));
            } else if ($numc >= 600 && $numc <= 699) {
                $numce = "SEISCIENTOS ";
                if ($numc > 600)
                    $numce = $numce . (self::decena($numc - 600));
            } else if ($numc >= 500 && $numc <= 599) {
                $numce = "QUINIENTOS ";
                if ($numc > 500)
                    $numce = $numce . (self::decena($numc - 500));
            } else if ($numc >= 400 && $numc <= 499) {
                $numce = "CUATROCIENTOS ";
                if ($numc > 400)
                    $numce = $numce . (self::decena($numc - 400));
            } else if ($numc >= 300 && $numc <= 399) {
                $numce = "TRESCIENTOS ";
                if ($numc > 300)
                    $numce = $numce . (self::decena($numc - 300));
            } else if ($numc >= 200 && $numc <= 299) {
                $numce = "DOSCIENTOS ";
                if ($numc > 200)
                    $numce = $numce . (self::decena($numc - 200));
            } else if ($numc >= 100 && $numc <= 199) {
                if ($numc == 100)
                    $numce = "CIEN ";
                else
                    $numce = "CIENTO " . (self::decena($numc - 100));
            }
        } else
            $numce = self::decena($numc);

        return $numce;
    }

    private static function miles($nummero)
    {
        if ($nummero >= 1000 && $nummero < 2000) {
            $numm = "MIL " . (self::centena($nummero % 1000));
        }
        if ($nummero >= 2000 && $nummero < 10000) {
            $numm = self::unidad(Floor($nummero / 1000)) . " MIL " . (self::centena($nummero % 1000));
        }
        if ($nummero < 1000)
            $numm = self::centena($nummero);

        return $numm;
    }

    private static function decmiles($numdmero)
    {
        if ($numdmero == 10000)
            $numde = "DIEZ MIL";
        if ($numdmero > 10000 && $numdmero < 20000) {
            $numde = self::decena(Floor($numdmero / 1000)) . "MIL " . (self::centena($numdmero % 1000));
        }
        if ($numdmero >= 20000 && $numdmero < 100000) {
            $numde = self::decena(Floor($numdmero / 1000)) . " MIL " . (self::miles($numdmero % 1000));
        }
        if ($numdmero < 10000)
            $numde = self::miles($numdmero);

        return $numde;
    }

    private static function cienmiles($numcmero)
    {
        if ($numcmero == 100000)
            $num_letracm = "CIEN MIL";
        if ($numcmero >= 100000 && $numcmero < 1000000) {
            $num_letracm = self::centena(Floor($numcmero / 1000)) . " MIL " . (self::centena($numcmero % 1000));
        }
        if ($numcmero < 100000)
            $num_letracm = self::decmiles($numcmero);
        return $num_letracm;
    }

    private static function millon($nummiero)
    {
        if ($nummiero >= 1000000 && $nummiero < 2000000) {
            $num_letramm = "UN MILLON " . (self::cienmiles($nummiero % 1000000));
        }
        if ($nummiero >= 2000000 && $nummiero < 10000000) {
            $num_letramm = self::unidad(Floor($nummiero / 1000000)) . " MILLONES " . (self::cienmiles($nummiero % 1000000));
        }
        if ($nummiero < 1000000)
            $num_letramm = self::cienmiles($nummiero);

        return $num_letramm;
    }

    private static function decmillon($numerodm)
    {
        if ($numerodm == 10000000)
            $num_letradmm = "DIEZ MILLONES";
        if ($numerodm > 10000000 && $numerodm < 20000000) {
            $num_letradmm = self::decena(Floor($numerodm / 1000000)) . "MILLONES " . (self::cienmiles($numerodm % 1000000));
        }
        if ($numerodm >= 20000000 && $numerodm < 100000000) {
            $num_letradmm = self::decena(Floor($numerodm / 1000000)) . " MILLONES " . (self::millon($numerodm % 1000000));
        }
        if ($numerodm < 10000000)
            $num_letradmm = self::millon($numerodm);

        return $num_letradmm;
    }

    private static function cienmillon($numcmeros)
    {
        if ($numcmeros == 100000000)
            $num_letracms = "CIEN MILLONES";
        if ($numcmeros >= 100000000 && $numcmeros < 1000000000) {
            $num_letracms = self::centena(Floor($numcmeros / 1000000)) . " MILLONES " . (self::millon($numcmeros % 1000000));
        }
        if ($numcmeros < 100000000)
            $num_letracms = self::decmillon($numcmeros);
        return $num_letracms;
    }

    private static function milmillon($nummierod)
    {
        if ($nummierod >= 1000000000 && $nummierod < 2000000000) {
            $num_letrammd = "MIL " . (self::cienmillon($nummierod % 1000000000));
        }
        if ($nummierod >= 2000000000 && $nummierod < 10000000000) {
            $num_letrammd = self::unidad(Floor($nummierod / 1000000000)) . " MIL " . (self::cienmillon($nummierod % 1000000000));
        }
        if ($nummierod < 1000000000)
            $num_letrammd = self::cienmillon($nummierod);

        return $num_letrammd;
    }


    /**
     * Esta función recibe un valor entero, double o decimal y retorna su expresión en texto.
     * @param string $numero El numero entero o decimal del cual se obtendrá su valor en texto
     * @return string El valor expresado en texto, tal como se muestra en los cheques.
     */
    public static function  obtenerValorMonetarioTexto($numero)
    {
        $num = str_replace(",", "", $numero);
        $num = number_format($num, 2, '.', '');
        $cents = substr($num, strlen($num) - 2, strlen($num) - 1);
        $num = (int)$num;

        $numf = self::milmillon($num);

        return " SON:  " . $numf . " CON " . $cents . "/100 DOLARES";
    }
}
