<?php

namespace Src\App;

use App\Mail\Admin\SendExceptionMail;
use Exception;
use Illuminate\Support\Facades\Log;
use Mail;

class ExceptionNotificationService
{
    public function __construct()
    {
    }

    /**
     * Recibe un string del error recibido para enviar un correo al admin con el usuario logueado, error, fecha y hora
     * @param string $exception
     * @return void
     */
    public static function sendExceptionErrorToSystemAdminMail(string $exception)
    {
        try {

            $admin_mail = "informatica@jpconstrucred.com";
            Mail::to($admin_mail)->send(new SendExceptionMail($exception));

        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ["error en sendExceptionErrorToSystemAdminMail", $e->getMessage(), $e->getLine()]);
        }
    }

}
