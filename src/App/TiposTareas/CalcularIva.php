<?php

namespace Src\App\TiposTareas;

use App\Models\Servicio;
use Illuminate\Support\Facades\Config;

class CalcularIva
{
	/* private Servicio $model;

	public function __construct(Servicio $model)
	{
		$this->model = $model;
	}

	public function __invoke(): float
	{
		if ($this->model->gravable) {
			return $this->model->precio_unitario * (Config::get('buro.porcentaje_iva') / 100);
		}
		return 0;
	} */
}
