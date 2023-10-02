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
            "accesses" => AccessResource::collection($this->accepted_accesses),
            // "notifications" => $this->unreadNotifications()->paginate(15)->get(),
            "notifications" => $this->unreadNotifications()->orderBy('created_at', 'desc')->take(10)->get(),
            'image' => $this->image ? URL::to($this->image->url) : null,


        ];
    }
}
