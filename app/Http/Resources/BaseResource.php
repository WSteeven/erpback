<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    private $request;

    public function toArray($request)
    {
        $this->request = $request;
        $campos = $request->query('campos') ? explode(',', $request->query('campos')) : [];
        $modelo = $this->construirModelo($campos);

        // Cargar relaciones solo si están presentes en los campos solicitados
        $this->cargarRelaciones($campos, $modelo);

        return empty($campos) ? $modelo : $this->filtrarCampos($modelo, $campos);
    }

    protected function construirModelo($campos)
    {
        // Implementación en la clase base, puedes dejarlo vacío o añadir campos comunes
        return [];
    }

    protected function cargarRelaciones($campos, &$modelo)
    {
        $relaciones = $this->getRelations();

        foreach ($relaciones as $relacion => $valor) {
            if (in_array($relacion, $campos)) {
                $modelo[$relacion] = $this->$relacion;
            }
        }
    }

    protected function filtrarCampos($modelo, $campos)
    {
        return array_filter($modelo, function ($valor, $campo) use ($campos) {
            return in_array($campo, $campos);
        }, ARRAY_FILTER_USE_BOTH);
    }

    // Nuevo método para verificar si el controlador es 'show'
    public function controllerMethodIsShow()
    {
        return $this->request->route()->getActionMethod() == 'show';
    }
}
