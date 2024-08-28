<?php

namespace Src\App\RecursosHumanos\SeleccionContratacion;

use App\Mail\RecursosHumanos\SeleccionContratacion\BancoPostulanteMail;
use App\Mail\RecursosHumanos\SeleccionContratacion\PostulacionDescartadaMail;
use App\Mail\RecursosHumanos\SeleccionContratacion\PostulacionLeidaMail;
use App\Mail\RecursosHumanos\SeleccionContratacion\PostulacionSeleccionadaMail;
use App\Models\RecursosHumanos\SeleccionContratacion\BancoPostulante;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        try{
            Mail::to($postulacion->user->email)->send(new PostulacionDescartadaMail($postulacion, $antes_entrevista));
        }catch (Throwable $e){
            Log::channel('testing')->info('Log', ['Error notificarPostulacionDescartada sendMail', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function notificarPostulanteSeleccionado(Postulacion $postulacion)
    {
        try{
            Mail::to($postulacion->user->email)->send(new PostulacionSeleccionadaMail($postulacion));
        }catch (Throwable $e){
            Log::channel('testing')->info('Log', ['Error notificarPostulacionDescartada sendMail', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
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


}
