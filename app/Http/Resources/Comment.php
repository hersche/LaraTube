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
            'body' => nl2br($this->body),
            'media_id' => $this->media_id,
            'user_id' => $this->user_id,
            'parent_id' => $this->parent_id,
            'likes' => $this->likes(),
            'dislikes' => $this->dislikes(),
            'myLike' => $this->myLike(),
            'childs' => $this::collection($this->childs()),
            'created_at' => $this->created_at,
            'created_at_readable' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at,
        ];
    }
}
