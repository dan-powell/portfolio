<?php namespace DanPowell\Portfolio\App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

	public function tags()
    {
        return $this->morphToMany('DanPowell\Portfolio\App\Models\Tag', 'taggable');
    }

    public function sections()
    {
        return $this->morphMany('DanPowell\Portfolio\App\Models\Section', 'attachment')->orderBy('rank', 'ASC');
    }

    public function pages()
    {
        return $this->morphMany('DanPowell\Portfolio\App\Models\Page', 'attachment');
    }


}
