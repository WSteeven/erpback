<?php

namespace Src\App\RecursosHumanos\SeleccionContratacion;

use App\Events\RecursosHumanos\SeleccionContratacion\NotificarPostulanteSeleccionadoMedicoEvent;
use App\Mail\RecursosHumanos\SeleccionContratacion\BancoPostulanteMail;
use App\Mail\RecursosHumanos\SeleccionContratacion\EnviarLinkTestPersonalidadMail;
use App\Mail\RecursosHumanos\SeleccionContratacion\PostulacionDescartadaMail;
use App\Mail\RecursosHumanos\SeleccionContratacion\PostulacionLeidaMail;
use App\Mail\RecursosHumanos\SeleccionContratacion\PostulacionSeleccionadaMail;
use App\Models\RecursosHumanos\SeleccionContratacion\BancoPostulante;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use App\Models\RecursosHumanos\SeleccionContratacion\Vacante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Str;
use Throwable;

class PostulacionService
{

    // protected Postulacion $postulacion
    public function __construct()
    {
    }

    /**
     * @throws Throwable
     */
    public function notificarPostulacionLeida(Postulacion $postulacion)
    {
        try {
            // Aqui se hace todo el proceso de notificar la postulacion
            Mail::to($postulacion->user->email)->send(new PostulacionLeidaMail($postulacion));

        } catch (Throwable $e) {
            Log::channel('testing')->info('Log', ['Error notificarPostulacionLeida sendMail', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function notificarPostulacionDescartada(Postulacion $postulacion, bool $antes_entrevista)
    {
        try {
            Mail::to($postulacion->user->email)->send(new PostulacionDescartadaMail($postulacion, $antes_entrevista));
        } catch (Throwable $e) {
            Log::channel('testing')->info('Log', ['Error notificarPostulacionDescartada sendMail', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function notificarPostulanteSeleccionado(Postulacion $postulacion)
    {
        try {
            Mail::to($postulacion->user->email)->send(new PostulacionSeleccionadaMail($postulacion));
        } catch (Throwable $e) {
            Log::channel('testing')->info('Log', ['Error notificarPostulacionDescartada sendMail', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
    }

    /**
     * Notificar al médico ocupacional que hay un candidato seleccionado al que debe realizarle los examenes medicos correspondientes
     * @param int $postulacion_id
     * @throws Throwable
     */
    public function notificarPostulanteSeleccionadoMedico(int $postulacion_id)
    {
        event(new NotificarPostulanteSeleccionadoMedicoEvent($postulacion_id));
    }

    /**
     * @throws Throwable
     */
    public function notificarBancoPostulante(Postulacion $postulacion)
    {
        try {
            Mail::to($postulacion->user->email)->send(new BancoPostulanteMail($postulacion));
        } catch (Throwable $e) {
            Log::channel('testing')->info('Log', ['Error notificarBancoPostulante sendMail', $e->getMessage(), $e->getLine()]);
            throw $e;
        }

    }

    /**
     * Verifica si un usuario está en banco de postulantes.
     * @param int $user_id
     * @param string $user_type
     * @return bool
     */
    public function estaEnBanco(int $user_id, string $user_type)
    {
        return BancoPostulante::where('user_id', $user_id)->where('user_type', $user_type)->where('descartado', false)->first() !== null;
    }

    /**
     * Esta funcion actualiza la vacante a completa y notifica a todos los postulantes que ya ha sido completada la vacante
     * @param Postulacion $postulacion
     * @return void
     */
    public function actualizarVacante(Postulacion $postulacion)
    {
        $vacante = Vacante::find($postulacion->vacante_id)->first();
        $contratados_para_esta_vacante = Postulacion::where('vacante_id', $vacante->id)->where('estado', Postulacion::CONTRATADO)->count();
        if ($contratados_para_esta_vacante == $vacante->num_plazas)
            $postulacion->vacante()->update(['es_completada' => true, 'activo' => false]);
        // se hace la actualizacion solo una vez que se haya contratado todas las vacantes, seleccionadas y se inactiva la vacante para que ya no aparezca

    }


    /*        $respuestas = [
            "1" => "C", "2" => "C", "3" => "A", "4" => "C", "5" => "C",
            "6" => "C", "7" => "C", "8" => "A", "9" => "C", "10" => "C",
            "11" => "A", "12" => "A", "13" => "A", "14" => "A", "15" => "C",
            "16" => "C", "17" => "A", "18" => "B", "19" => "C", "20" => "C",
            "21" => "A", "22" => "A", "23" => "A", "24" => "C", "25" => "A",
            "26" => "C", "27" => "C", "28" => "A", "29" => "A", "30" => "A",
            "31" => "C", "32" => "C", "33" => "A", "34" => "C", "35" => "A",
            "36" => "A", "37" => "C", "38" => "A", "39" => "A", "40" => "A",
            "41" => "A", "42" => "A", "43" => "C", "44" => "A", "45" => "C",
            "46" => "C", "47" => "C", "48" => "A", "49" => "A", "50" => "C",
            "51" => "C", "52" => "A", "53" => "A", "54" => "A", "55" => "A",
            "56" => "C", "57" => "A", "58" => "A", "59" => "C", "60" => "A",
            "61" => "A", "62" => "A", "63" => "A", "64" => "A", "65" => "A",
            "66" => "C", "67" => "A", "68" => "C", "69" => "A", "70" => "A",
            "71" => "C", "72" => "C", "73" => "A", "74" => "A", "75" => "C",
            "76" => "A", "77" => "A", "78" => "A", "79" => "A", "80" => "A",
            "81" => "A", "82" => "A", "83" => "C", "84" => "A", "85" => "A",
            "86" => "C", "87" => "A", "88" => "A", "89" => "A", "90" => "A",
            "91" => "C", "92" => "A", "93" => "A", "94" => "C", "95" => "A",
            "96" => "A", "97" => "A", "98" => "A", "99" => "C", "100" => "C",
            "101" => "C", "102" => "A", "103" => "C", "104" => "A", "105" => "C",
            "106" => "A", "107" => "A", "108" => "C", "109" => "A", "110" => "A",
            "111" => "C", "112" => "A", "113" => "A", "114" => "C", "115" => "A",
            "116" => "C", "117" => "C", "118" => "A", "119" => "C", "120" => "C",
            "121" => "C", "122" => "A", "123" => "C", "124" => "C", "125" => "B",
            "126" => "A", "127" => "C", "128" => "C", "129" => "A", "130" => "C",
            "131" => "A", "132" => "A", "133" => "C", "134" => "A", "135" => "A",
            "136" => "A", "137" => "A", "138" => "C", "139" => "C", "140" => "A",
            "141" => "A", "142" => "A", "143" => "A", "144" => "A", "145" => "A",
            "146" => "C", "147" => "A", "148" => "A", "149" => "A", "150" => "A",
            "151" => "C", "152" => "A", "153" => "A", "154" => "C", "155" => "B",
            "156" => "C", "157" => "A", "158" => "A", "159" => "A", "160" => "C",
            "161" => "C", "162" => "A", "163" => "A", "164" => "A", "165" => "A",
            "166" => "A", "167" => "A", "168" => "A", "169" => "A", "170" => "A",
            "171" => "B", "172" => "B", "173" => "A", "174" => "C", "175" => "C",
            "176" => "B", "177" => "A", "178" => "A", "179" => "B", "180" => "B",
            "181" => "B", "182" => "A", "183" => "C", "184" => "B", "185" => "A"
        ];*/


    /**
     * Obtiene el ID de la postulación a partir del token proporcionado.
     * Si el token es nulo, devuelve null.
     *
     * @param string|null $token
     * @return null
     */
    public static function obtenerIdPostulacionByToken(?string $token)
    {
        if (is_null($token)) return null;
        $postulacion = Postulacion::where('token_test', $token)->first();
        return $postulacion?->id;
    }

    /**
     * Genera un token aleatorio de 20 caracteres si no existe para la postulación proporcionada.
     * Si ya existe, no hace nada y devuelve la postulación.
     *
     * @param Postulacion $postulacion
     * @return Postulacion
     */
    public function generarTokenSiNoExiste(Postulacion $postulacion)
    {
        if (is_null($postulacion->token_test)) {
            $postulacion->update(['token_test' => Str::random(20)]);
        }
        return $postulacion;
    }

    /**
     * Crea una evaluación de personalidad si no existe para la postulación proporcionada.
     * Si ya existe, devuelve la existente.
     *
     * @param Postulacion $postulacion
     * @return Model
     */
    public function crearEvaluacionSiNoexiste(Postulacion $postulacion)
    {
        return $postulacion->evaluacionPersonalidad()->firstOrCreate(
            ['postulacion_id' => $postulacion->id,
                'user_id' => $postulacion->user_id,
                'user_type' => $postulacion->user_type,
            ],
            [
                'respuestas' => '[]',
            ]
        );
    }

    /**
     * Envia el link del test de personalidad por correo electrónico al postulante si no fue enviado previamente.
     *
     * @param Postulacion $postulacion
     * @param string $link
     * @return void
     */
    public function enviarLinkSiNoFueEnviado(Postulacion $postulacion, string $link): void
    {
        if (!$postulacion->evaluacionPersonalidad?->enviada_mail) {
            Mail::to($postulacion->user->email)->send(new EnviarLinkTestPersonalidadMail($postulacion, $link));
            $postulacion->evaluacionPersonalidad()->update(['enviada_mail' => true]);
        }
    }
}
