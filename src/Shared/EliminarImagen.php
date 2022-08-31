<?php

namespace Src\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EliminarImagen
{
	private Model $model;
	private bool $privado;

	public function __construct(Model $model, bool $privado)
	{
		$this->model = $model;
		$this->privado = $privado;
	}

	public function execute()
	{
		$ruta_imagen = $this->privado ? $this->model->images->url : str_replace('storage', 'public', $this->model->images->url);
		Storage::delete($ruta_imagen);
		$this->model->images()->delete();
	}
}
