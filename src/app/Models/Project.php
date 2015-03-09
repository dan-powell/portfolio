<?php namespace DanPowell\Portfolio\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

	public function tags()
    {
        return $this->morphToMany('DanPowell\Portfolio\Models\Tag', 'taggable');
    }

    public function sections()
    {
        return $this->morphMany('DanPowell\Portfolio\Models\Section', 'attachment')->orderBy('rank', 'ASC');
    }

    public function pages()
    {
        return $this->morphMany('DanPowell\Portfolio\Models\Page', 'attachment');
    }


}
