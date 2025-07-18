<?php

namespace Src\App;

use App\Mail\Admin\SendExceptionMail;
use App\Mail\Admin\SendInformationMail;
use Exception;
use Illuminate\Support\Facades\Log;
use Mail;

class SystemNotificationService
{
    private static string $admin_mail = "informatica@jpconstrucred.com";

    public function __construct()
    {
    }

    /**
     * Recibe un string del error recibido para enviar un correo al admin con el usuario logueado, error, fecha y hora
     * @param string $exception
     * @return void
     */
    public static function sendExceptionErrorMailToSystemAdmin(string $exception)
    {
        try {
            Mail::to(self::$admin_mail)->send(new SendExceptionMail($exception));

        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ["error en sendExceptionErrorToSystemAdminMail", $e->getMessage(), $e->getLine()]);
        }
    }

    public static function sendInformationMailToSystemAdmin(string $message, string $tipo, array $datos)
    {
        try {
            Mail::to(self::$admin_mail)->send(new SendInformationMail($message, $tipo, $datos));
        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ["error en sendInformationMailToSystemAdmin", $e->getMessage(), $e->getLine()]);
        }
    }

}
