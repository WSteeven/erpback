<?php

namespace App\Jobs\RecursosHumanos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Src\App\RecursosHumanos\NominaPrestamos\NominaService;
use Src\App\SystemNotificationService;
use Throwable;

class EnviarRolPagoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $rolPagoId;
    private NominaService $nominaService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public RolPago $rolPago)
    {
        $this->rolPagoId = $rolPago->id;
        $this->nominaService = new NominaService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 1. Definir el nombre del archivo de log
        $mes = $this->rolPago->mes ?? Carbon::now()->format('Y-m');
        $logPath = storage_path("logs/roles_pago/logs_rol_pago_$mes.log");

        // 2. Crear instancia de logger dinámico
        $logger = new Logger("rol_pago_$mes");
        $logger->pushHandler(new StreamHandler($logPath, Logger::INFO));

        try {

            $empleado = Empleado::find($this->rolPago->empleado_id);
            $this->nominaService->enviar_rol_pago($this->rolPago, $empleado);
            $logger->info("Rol de pago enviado correctamente a: {$empleado->user->email}");
//            Log::info("Rol de pago enviado correctamente a: {$empleado->user->email}");
        }catch (Throwable $th) {
            $email = $empleado->user->email ?? 'desconocido';
            $logger->error("Error enviando rol de pago a $email: " . $th->getMessage());
//            Log::error("Error enviando rol de pago a $email: " . $th->getMessage());
            SystemNotificationService::sendExceptionErrorMailToSystemAdmin(
                "Error al enviar rol de pago a $email: " . $th->getMessage()
            );
        }
    }
}
