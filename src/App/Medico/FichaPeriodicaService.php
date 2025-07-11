<?php

namespace Src\App\Medico;

use App\Http\Requests\Medico\FichaPeriodicaRequest;
use App\Models\Medico\FichaPeriodica;
use Illuminate\Support\Facades\Log;
use Throwable;

class FichaPeriodicaService
{
    // private $ficha_preocupacional_id;
    private FichaPeriodica $ficha;
    private PolymorphicMedicoModelsService $servicioPolimorfico;

    public function __construct(FichaPeriodica $ficha)
    {
        $this->ficha = $ficha;
        $this->servicioPolimorfico = new PolymorphicMedicoModelsService();
    }

    /**
     * @throws Throwable
     */
    public function guardarDatosFichaPeriodica(FichaPeriodicaRequest $request){
        try {
            $this->servicioPolimorfico->syncronizarInformacionFicha($this->ficha, $request);
        } catch (Throwable $th) {
            Log::channel('testing')->info('Log', ['Error guardar datos ficha periodica', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
}
