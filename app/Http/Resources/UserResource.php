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
    $roleTranslations = [
        'manager' => 'Менеджер',
        'admin' => 'Админ',
        'treasurer' => 'Директор Ресторана',
        'hr' => 'HR',
    ];
    $role = is_array($this->role()) ? $this->role()[0] : $this->role();
    return [
        'id' => $this->id,
        'name' => $this->name,
        'phone' => $this->phone,
        'email' => $this->email,
        'role' => [$roleTranslations[$role] ?? $role],
        'image' => $this->image_url,
    ];
}
}
