<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class RoomResource extends JsonResource
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
            'description' => $this->description,
            "status"=>$this->pivot->status,
            'users'=>$this->accesses
            // 'users' => $this->access->map(function ($access) {
            //     return [
            //         'id' => $access->user->id,
            //         'name' => $access->user->name,
            //         'role' => $access->role, 
            //     ];
            // }),

        ];
    }
}