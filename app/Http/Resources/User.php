<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      $mediaIds = array();
      foreach($this->medias() as $media){
        array_push($mediaIds, $media->id);
      }
      return [
          'id' => $this->id,
          'name' => $this->name,
          'avatar' => $this->avatar(),
          'background' => $this->background(),
          'bio' => $this->bio,
          'tagString' => $this->tagString(),
          'mediaIds' => $mediaIds,
          'public' => $this->public,
          'api_token' => $this->api_token,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
      ];
    }
}
