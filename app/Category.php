<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'title', 'description','avatar_source', 'parent_id'
    ];
    public function parent() {
      return Category::find($this->parent_id);
    }

    protected $table = 'categories';
}
