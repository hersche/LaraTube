<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSettings extends JsonResource
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
          'user_id' => $this->user_id,
          'language' => $this->lang,
          'autoplay' => $this->autoplay,
          'theme' => $this->theme,
      ];
    }
}
