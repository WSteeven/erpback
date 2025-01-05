<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermisoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo =[
            'id'=>$this->id,
            'name'=>$this->name,
            'nombre'=>$this->name,
        ];
        if($controller_method=='show'){
            $modelo['roles']= $this->roles()->pluck('name');
            $ids_users_inactivos = Empleado::where('estado', false)->pluck('usuario_id');
            $modelo['empleados']= $this->users()->whereNotIn('id',$ids_users_inactivos)->pluck('name');
        }
        return $modelo;
    }
}
