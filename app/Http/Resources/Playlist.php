<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Playlist extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray([
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'media_ids' => $this->media_ids()
        ]);
    }
}
