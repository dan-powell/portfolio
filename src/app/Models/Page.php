<?php namespace DanPowell\Portfolio\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model {

	public function attachment()
    {
        return $this->morphTo();
    }

    public function sections()
    {
        return $this->morphMany('DanPowell\Portfolio\Models\Section', 'attachment')->orderBy('rank', 'ASC');
    }

}
