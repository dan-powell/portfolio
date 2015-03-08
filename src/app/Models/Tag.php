<?php namespace DanPowell\Portfolio\App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	public function projects()
    {
        return $this->morphedByMany('DanPowell\Portfolio\App\Models\Project', 'taggable');
    }

}
