<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->role()[0] == 'admin'){
            $role_ru = 'Админ';
        }
        elseif($this->role()[0] == 'manager'){
            $role_ru = 'Руководитель';
        }
        elseif($this->role()[0] == 'hr'){
            $role_ru = 'HR';
        }
        elseif($this->role()[0] == 'treasurer'){
            $role_ru = 'Директор Ресторана';
        }
        else{
            $role_ru = 'Работник';
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'role' => $this->role(),
            'role_ru' => $role_ru,
            'image' => $this->image_url,
        ];
    }
}
