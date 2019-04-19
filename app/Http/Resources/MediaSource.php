<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaSource extends JsonResource
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
        'source' => $this->source,
        'source_type' => $this->source_type,
        'source_prio' => $this->source_prio,
        'type' => $this->type,
        'simpleType' => $this->simpleType(),
        'techType' => $this->techType(),
        'duration' => $this->duration,
      ];
        //return parent::toArray($request);
    }
}
