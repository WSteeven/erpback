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
    public function marcar_notificacion_leida()
    {
        $notificacion_remitente = Notificacion::where('per_originador_id', $this->gasto->id_usuario)
            ->where('per_destinatario_id', $this->gasto->aut_especial)
            ->where('tipo_notificacion', 'AUTORIZACION GASTO')
            ->where('leida', 0)
            ->where('notificable_id', $this->gasto->id)
            ->first();
        if ($notificacion_remitente !== null) {
            $notificacion_remitente->leida = 1;
            $notificacion_remitente->save();
        }
        $notificacion_destinatario = Notificacion::where('per_originador_id',$this->gasto->aut_especial )
        ->where('per_destinatario_id', $this->gasto->id_usuario )
        ->where('tipo_notificacion', 'AUTORIZACION GASTO')
        ->where('leida', 0)
        ->where('notificable_id', $this->gasto->id)
        ->first();
        if ($notificacion_destinatario !== null) {
            $notificacion_destinatario->leida = 1;
            $notificacion_destinatario->save();
        }
    }
}
