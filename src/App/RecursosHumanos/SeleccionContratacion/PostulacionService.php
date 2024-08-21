<?php

namespace Src\App\RecursosHumanos\SeleccionContratacion;

use App\Mail\RecursosHumanos\SeleccionContratacion\PostulacionLeidaMail;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PostulacionService{

    // protected Postulacion $postulacion

    public function __construct()
    {

    }

    public function notificarPostulacionLeida(Postulacion $postulacion){
        try {
            // Aqui se hace todo el proceso de notificar la postulacion
            Mail::to($postulacion->user->email)->send(new PostulacionLeidaMail($postulacion));
            
        } catch (\Throwable $e) {
            Log::channel('testing')->info('Log', ['Error notificarPostulacionLeida sendMail', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
    }



}
