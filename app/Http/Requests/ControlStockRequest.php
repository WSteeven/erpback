<?php

namespace App\Http\Requests;

use App\Models\ControlStock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ControlStockRequest extends FormRequest
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
        $request = $this;
        $rules = [
            'detalle_id' => ['required', Rule::unique('control_stocks')->where(function ($query) use ($request) {
                return $query->where('sucursal_id', $request->sucursal_id)
                    ->where('cliente_id', $request->cliente_id);
            })],
            'sucursal_id' => 'required|exists:sucursales,id|unique:control_stocks,detalle_id',
            'cliente_id' => 'required|exists:clientes,id|unique:control_stocks,detalle_id',
            'minimo' => 'sometimes|numeric',
            'reorden' => 'sometimes|numeric',
            'estado' => ['sometimes', Rule::in([ControlStock::SUFICIENTE, ControlStock::REORDEN, ControlStock::MINIMO])],
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $control_stock = $this->route()->parameter('control_stock');
            $rules['detalle_id'] = ['required', Rule::unique('control_stocks')->ignore($control_stock)->where(function ($query) use ($request) {
                return $query->where('sucursal_id', $request->sucursal_id)
                    ->where('cliente_id', $request->cliente_id);
            })];
        }

        return $rules;
    }

    public function prepareForValidation()
    {
        Log::channel('testing')->info('Log', ['entro en el prepare for validation']);
        Log::channel('testing')->info('Log', ['request recibida', $this->all()]);
        Log::channel('testing')->info('Log', ['calculo realizado', ControlStock::controlExistencias($this->detalle_id, $this->sucursal_id, $this->cliente_id)]);
        // if (is_null($this->estado)) {
            if(ControlStock::controlExistencias($this->detalle_id, $this->sucursal_id, $this->cliente_id)<=$this->minimo){
                $this->merge(['estado'=>ControlStock::MINIMO]);
                Log::channel('testing')->info('Log', ['entro en minimo']);
            }
            if(ControlStock::controlExistencias($this->detalle_id, $this->sucursal_id, $this->cliente_id)>$this->minimo && ControlStock::controlExistencias($this->detalle_id, $this->sucursal_id, $this->cliente_id)<=$this->reorden){
                $this->merge(['estado'=>ControlStock::REORDEN]);
                Log::channel('testing')->info('Log', ['entro en reorden']);
            }
            if(ControlStock::controlExistencias($this->detalle_id, $this->sucursal_id, $this->cliente_id)>$this->reorden){
                $this->merge(['estado'=>ControlStock::SUFICIENTE]);
                Log::channel('testing')->info('Log', ['entro en suficiente']);
            }
        // }
    }
}
