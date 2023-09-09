<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'rooms' => $this->rooms->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'role' => $room->pivot->role, // Include the access role from the pivot table
                ];
            }),

        ];
    }
}