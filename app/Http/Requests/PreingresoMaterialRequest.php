<?php

namespace App\Http\Requests;

use App\Models\DetalleProducto;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class PreingresoMaterialRequest extends FormRequest
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
            'observacion' => 'string|nullable',
            'cuadrilla' => 'string',
            'num_guia' => 'string',
            'courier' => 'string',
            'fecha' => 'string',
            'tarea' =>  'sometimes|nullable|numeric|exists:tareas,id',
            'cliente' => 'sometimes|nullable|numeric|exists:clientes,id',
            'autorizador' => 'nullable|sometimes|numeric|exists:empleados,id',
            'responsable' => 'required',
            'coordinador' => 'required|numeric|exists:empleados,id',
            'autorizacion' => 'sometimes|numeric|exists:autorizaciones,id',
            'listadoProductos.*.cantidad' => 'required',
        ];
    }
    
    public function withValidator($validator){
        $validator->after(function($validator){
            foreach ($this->listadoProductos as $item){
                $producto = Producto::where('nombre', $item['producto'])->first();
                //buscamos si el detalle ingresado coincide con uno ya almacenado
                $detalle = DetalleProducto::where('producto_id', $producto->id)->where(function ($query) use ($item) {
                    $query->where('descripcion', $item['descripcion'])->orWhere('descripcion', 'LIKE', '%' . $item['descripcion']   . '%'); // busca coincidencia exacta o similitud en el texto
                })->first();
                // verificamos si el detalle necesita obligatoriamente un numero de serie o no
                if(!is_null($detalle->serial) && is_null($item['serial'])) $validator->errors()->add('listadoProductos.*.serial', 'N° serial es requerido en el elemento '.$item['descripcion'].' Por favor, verifica y corrige la información');
            }
        });
    }

    protected function prepareForValidation()
    {
        $userCoordinadorBodega = User::whereHas('roles', function ($q) {
            $q->where('name', User::ROL_COORDINADOR_BODEGA);
        })->first();

        $this->merge([
            'fecha' => date('Y-m-d', strtotime($this->fecha))
        ]);

        if (is_null($this->tarea)) { //si no hay tarea, el autorizador es el coordinador de bodega y los materiales se cargarán al stock del tecnico
            $this->merge([
                'autorizador' => $userCoordinadorBodega->empleado->id
            ]);
        } else { //caso contrario, el material se asignará al stock de tarea y el autorizador es el coordinador
            $this->merge([
                'autorizador' => $this->coordinador
            ]);
        }
    }
}
