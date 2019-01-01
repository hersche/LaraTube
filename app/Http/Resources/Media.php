<?php

namespace App\Http\Resources;
use App\Http\Resources\Comment as CommentResource;
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
      $finalSource = $this->source;
      if($this->simpleType()!="torrent"){
        if(substr($finalSource,0,4)!="http"){
          $finalSource = url("/")."/".$this->source;
        }
      }
      $tagIds = array();
      foreach($this->tags as $tag){
        array_push($tagIds, $tag->id);
      }
      $comments = $this->comments()->sortBy('created_at');
      return [
          'id' => $this->id,
          'title' => $this->title,
          'source' => $this->source,
          'poster_source' => $this->poster(),
          'duration' => $this->duration,
          'type' => $this->type,
          'description' => $this->description,
          'myLike' => $this->myLike($request),
          'likes' => $this->likes(),
          'dislikes' => $this->dislikes(),
          'simpleType' => $this->simpleType(),
          'tags' => $this->tags,
          'tagsIds' => $tagIds,
          'tagString' => $this->tagString(),
          'user_id' => $this->user_id,
          'comments' => CommentResource::collection($comments),
          'created_at' => $this->created_at,
          'created_at_readable' => $this->created_at->diffForHumans(),
          'updated_at' => $this->updated_at,
      ];
    }
}
