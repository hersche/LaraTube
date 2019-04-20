<?php

namespace App\Http\Resources;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\Track as TrackResource;
use App\Http\Resources\MediaSource as MediaSourceResource;
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
    /*  $finalSource = $this->source;
      if($this->simpleType()!="torrent"){
        if(substr($finalSource,0,4)!="http"){
          $finalSource = url("/")."/".$this->source;
        }
      }*/
      $tagIds = array();
      foreach($this->tags as $tag){
        if(!empty($tag)){
          array_push($tagIds, $tag->id);
        }
      }
      $comments = $this->comments;//->sortBy('created_at');
      return [
          'id' => $this->id,
          'title' => $this->title,
          'sources' => MediaSourceResource::collection($this->sources),
          'chapters' => $this->chapters,
          'poster_source' => $this->poster(),
        //  'type' => $this->type,
          'description' => $this->description,
          'view' => $this->view,
          'base_type' => $this->base_type,
          'totalView' => $this->totalView(),
          'myLike' => $this->myLike($request),
          'likes' => $this->likes(),
          'dislikes' => $this->dislikes(),
          //'simpleType' => $this->simpleType(),
          //'techType' => $this->techType(),
          'tags' => $this->tags,
          'tagsIds' => $tagIds,
          'tagString' => $this->tagString(),
          'user_id' => $this->user_id,
          'category_id' => $this->category_id,
          /*'intro_start' => $this->intro_start,
          'outro_start' => $this->outro_start,
          'intro_end' => $this->intro_end,
          'outro_end' => $this->outro_end,*/
          'tracks' => TrackResource::collection($this->tracks()),
          'comments' => CommentResource::collection($comments),
          'created_at' => $this->created_at,
          'created_at_readable' => $this->created_at->diffForHumans(),
          'updated_at' => $this->updated_at,
      ];
    }
}
