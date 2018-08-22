<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'media' => $this->media_id,
            'user' => $this->user,
            'created_at' => $this->created_at,
            'created_at_readable' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at,
        ];
    }
}
