<?php

namespace Src\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Src\Config\RutasStorage;
use Src\Shared\EliminarImagen;

class ActualizarImagen
{
	private Model $model;
	private String | null $imagen_base64;
	private RutasStorage $ruta;
	private EliminarImagen $eliminar_imagen;
	private bool $privado;

	public function __construct(Model $model, String | null $imagen_base64, RutasStorage $ruta, bool $privado)
	{
		$this->model = $model;
		$this->imagen_base64 = $imagen_base64;
		$this->ruta = $ruta;
		$this->eliminar_imagen = new EliminarImagen($model, $privado);
		$this->privado = $privado;
	}

	public function execute()
	{
		if (!is_null($this->imagen_base64)) {
			if (Utils::esBase64($this->imagen_base64)) {
				$imagen_decodificada = Utils::decodificarImagen($this->imagen_base64);
				$extension = Utils::obtenerExtension($this->imagen_base64);

				$directorio = $this->ruta->value;
				$nombre_archivo = Utils::generarNombreArchivoAleatorio($extension);
				$ruta_relativa = Utils::obtenerRutaRelativaImagen($directorio, $nombre_archivo);
				$ruta_absoluta = Utils::obtenerRutaAbsolutaImagen($directorio, $nombre_archivo);

				if ($this->model->images) {
					$ruta_imagen_antigua = $this->privado ? $this->model->images->url : str_replace('storage', 'public', $this->model->images->url);
					Storage::delete($ruta_imagen_antigua);
				}

				Image::make($imagen_decodificada)->resize(600, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save($ruta_absoluta);

				if ($this->model->images)
					$this->model->images()->update(['url' => $ruta_relativa]);
				else
					$this->model->images()->create(['url' => $ruta_relativa]);
			}
		} else if ($this->model->images) {
			$this->eliminar_imagen->execute();
		}
	}
}
