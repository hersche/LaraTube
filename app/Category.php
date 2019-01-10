<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'title', 'description','avatar_source','background_source', 'parent_id'
    ];
    public function parent() {
      return Category::find($this->parent_id);
    }

    public function childs(){
      return Category::where('parent_id',$this->id)->get();
    }
    protected $table = 'categories';
}
