<?php

namespace Src\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Src\Config\RutasStorage;

class GuardarImagen
{
	private Model $model;
	private String $imagen_base64;
	private RutasStorage $ruta;

	public function __construct(Model $model, String $imagen_base64, RutasStorage $ruta)
	{
		$this->model = $model;
		$this->imagen_base64 = $imagen_base64;
		$this->ruta = $ruta;
	}

	public function execute()
	{
		$imagen_decodificada = Utils::decodificarImagen($this->imagen_base64);
		$extension = Utils::obtenerExtension($this->imagen_base64);

		$directorio = $this->ruta->value;
		$nombre_archivo = Utils::generarNombreArchivoAleatorio($extension);
		$ruta_relativa = Utils::obtenerRutaRelativaImagen($directorio, $nombre_archivo);
		$ruta_absoluta = Utils::obtenerRutaAbsolutaImagen($directorio, $nombre_archivo);


		if (!Storage::exists($directorio)) {
			Storage::disk('local')->makeDirectory($directorio);
			chmod(storage_path() . '/app/' . $directorio, 0777);
		}

		/*$directorioPadre = '/home/puibf8qy5nuf/nortop/storage/app/public/';
		
		$directorioProductos = $directorioPadre . $directorio;*/


		Image::make($imagen_decodificada)->resize(600, null, function ($constraint) {
			$constraint->aspectRatio();
		})->save($ruta_absoluta);

		$this->model->images()->create(['url' => $ruta_relativa]);
	}
}
