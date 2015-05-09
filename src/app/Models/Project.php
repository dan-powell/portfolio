<?php namespace DanPowell\Portfolio\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

    protected $fillable = [
        'title',
        'slug',
        'seo_title',
        'seo_description',
        'markup',
        'scripts',
        'url',
        'featured',
        'template'
    ];

    public static $rules = [
        'title' => 'required',
        'slug' => 'required',
        'featured' => 'integer',
        'url' => 'url'
    ];


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
