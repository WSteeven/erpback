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

    protected function cargarRelacionesOld($campos, &$modelo)
    {
        $relaciones = $this->getRelations();

        foreach ($relaciones as $relacion => $valor) {
            if (in_array($relacion, $campos)) {
                $modelo[$relacion] = $this->$relacion;
            }
        }
    }

    protected function cargarRelaciones($campos, &$modelo)
    {
        $relaciones = $this->getRelations();

        foreach ($relaciones as $relacion => $valor) {
            if (in_array($relacion, $campos)) {
                $modelo[$relacion] = $this->obtenerInformacionCampo($relacion);
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

    protected function obtenerInformacionCampoOld($campo)
    {
        $metodo = 'obtenerInformacion' . ucfirst($campo);

        if (method_exists($this, $metodo)) {
            return $this->$metodo();
        }

        return $this->$campo;
    }

    protected function obtenerInformacionCampo($campo)
    {
        // Verifica si es una relación y si existe un método específico
        $metodo = 'obtenerInformacion' . ucfirst($campo);
        if (method_exists($this, $metodo)) {
            return $this->$metodo();
        }

        // Si es una relación y no hay un método específico, intenta acceder a propiedades específicas de la relación
        if (isset($this->$campo)) {
            return $this->$campo->toArray();
        }

        // Si no es una relación, devuelve el valor del campo si está definido o null
        return $this->$campo ?? null;
    }
}
