<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Media extends JsonResource
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
          'title' => $this->title,
          'source' => $this->source,
          'poster_source' => $this->poster(),
          'type' => $this->type,
          'description' => $this->description,
          'myLike' => $this->myLike(),
          'simpleType' => $this->simpleType(),
          'created_at' => $this->created_at,
          'created_at_readable' => $this->created_at->diffForHumans(),
          'updated_at' => $this->updated_at,
      ];
    }
}
