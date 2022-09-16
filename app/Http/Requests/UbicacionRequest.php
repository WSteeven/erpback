<?php

namespace App\Http\Requests;

use App\Models\Percha;
use App\Models\Piso;
use App\Models\Ubicacion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UbicacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            'codigo' => 'sometimes|string|unique:ubicaciones,codigo',
            'percha' => 'required|exists:perchas,id',
            'piso' => 'sometimes|required|exists:pisos,id'

            //$request->validate(['codigo' => 'required|string|unique:ubicaciones,codigo']);
        ];
    }

    public function messages()
    {
        return [
            'codigo.unique' => 'Este codigo ya existe en el sistema, verifica nuevamente'
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->codigo) {
            if ($this->percha && $this->piso) {
                $percha_encontrada = Percha::find($this->percha);
                $piso_encontrado = Piso::find($this->piso);

                if ($percha_encontrada && $piso_encontrado) {
                    $this->merge([
                        'codigo' => Ubicacion::obtenerCodigoUbicacionPerchaPiso($this->percha, $this->piso),
                    ]);
                }
            } else {
                $percha_encontrada = Percha::find($this->percha);
                if ($percha_encontrada) {
                    $this->merge([
                        'codigo' => Ubicacion::obtenerCodigoUbicacionPercha($this->percha),
                    ]);
                }
            }
        }
    }
}
