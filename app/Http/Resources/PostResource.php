<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            "status" => $this->status,
            "added_by" => new OtherUserResource($this->author),
            "user_handling" => $this->user_handling,
            "room_id" => $this->room_id,
            'image' => $this->image ? URL::to($this->image->url) : null,
            'created_at' => $this->created_at->format('y-m-d'),
            'updated_at' => $this->updated_at->format('y-m-d'),
        ];
    }
}
