<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermisoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
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
            $modelo['empleados']= $this->users()->pluck('name');
        }
        return $modelo;
    }
}
