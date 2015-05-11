<?php namespace DanPowell\Portfolio\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model {

    protected $fillable = [
        'markup',
        'scripts',
        'styles',
        'container_classes',
        'section_classes',
        'rank'
    ];

    public static $rules = [
        'rank' => 'integer',
        'scripts' => 'required',
    ];

	public $timestamps = false;

	public function attachment()
    {
        return $this->morphTo();
    }

}
