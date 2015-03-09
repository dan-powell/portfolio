<?php namespace DanPowell\Portfolio\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	public function projects()
    {
        return $this->morphedByMany('DanPowell\Portfolio\Models\Project', 'taggable');
    }

}
