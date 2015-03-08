<?php namespace DanPowell\Portfolio\App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model {

	public $timestamps = false;

	public function attachment()
    {
        return $this->morphTo();
    }

}