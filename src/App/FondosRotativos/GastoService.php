<?php

namespace Src\App\FondosRotativos;

use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\Notificacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class GastoService
{
    private $gasto;
    public function __construct(Gasto $gasto)
    {
        $this->gasto = $gasto;
    }
    public function marcarNotificacionLeida()
    {
        $notificacion_remitente = Notificacion::where('per_originador_id', $this->gasto->id_usuario)
            ->where('per_destinatario_id', $this->gasto->aut_especial)
            ->where('tipo_notificacion', 'AUTORIZACION GASTO')
            ->where('leida', 0)
            ->where('notificable_id', $this->gasto->id)
            ->first();
        if ($notificacion_remitente) {
            $notificacion_remitente->leida = 1;
            $notificacion_remitente->save();
        }
    }
}
