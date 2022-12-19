<?php

namespace Src\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EliminarArchivo
{
	private Model $model;
	private bool $privado;

	public function __construct(Model $model, bool $privado = false)
	{
		$this->model = $model;
		$this->privado = $privado;
	}

	public function execute()
	{
		$ruta = $this->privado ? $this->model->ruta : str_replace('storage', 'public', $this->model->ruta);
		Storage::delete($ruta);
		$this->model->delete();
	}
}
