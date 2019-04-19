<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'title', 'description','avatar','background', 'parent_id'
    ];
    public function parent() {
      return Category::find($this->parent_id);
    }

    public function childs(){
      return Category::where('parent_id',$this->id)->get();
    }
    protected $table = 'categories';
}
