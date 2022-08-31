<?php

namespace Src\Shared;

use App\Models\Carpeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Src\Config\RutasStorage;

class GuardarArchivo
{
	// private Model $model;
	private Request $request;
	private RutasStorage $ruta;

	public function __construct(Request $request, RutasStorage $ruta)
	{
		// $this->model = $model;
		$this->request = $request;
		$this->ruta = $ruta;
	}

	public function execute()
	{
		$archivo = $this->request->file('file');
		$carpeta = $this->request['carpeta'];
		$carpeta = Carpeta::find($carpeta);

		$path = $archivo->store($this->ruta->value);
		$ruta_relativa = Utils::obtenerRutaRelativaImagen($path);
		$carpeta->archivos()->create([
			'nombre' => $archivo->getClientOriginalName(),
			'ruta' => $ruta_relativa,
			'tamanio_bytes' => filesize($archivo)
		]);


		return response()->json(['mensaje' => 'Video actualizado exitosamente!']);
	}
}
