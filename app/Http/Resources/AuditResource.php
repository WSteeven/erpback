<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'user_name'=>$this->user?->name,
            'event'=>$this->event,
            'auditable_type'=>$this->auditable_type,
            'auditable_id'=>$this->auditable_id,
            'url'=>$this->url,
            'ip_address'=>$this->ip_address,
            'user_agent'=>$this->user_agent,
            'old_values'=>$this->old_values,
            'new_values'=>$this->new_values,
            'created_at' => date('Y-m-d h:i:s a', strtotime($this->created_at)),
            'updated_at' => date('Y-m-d h:i:s a', strtotime($this->updated_at)),
        ];
    }
}
