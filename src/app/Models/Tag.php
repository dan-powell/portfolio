<?php namespace DanPowell\Portfolio\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model {

    protected $fillable = [
        'title',
        'slug'
    ];

    public function rules($id = null)
	{
	    return [
    	    'title' => 'required|unique:tags,title,' . $id,
    	    'slug' => 'required|unique:tags,slug,' . $id
	    ];
	}

    protected $casts = [
        'id' => 'integer'
    ];

    protected $appends = ['created_at_human', 'updated_at_human'];

    public function getUpdatedAtHumanAttribute()
    {
        return $this->updated_at->toFormattedDateString();
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->toFormattedDateString();
    }


	public function projects()
    {
        return $this->morphedByMany('DanPowell\Portfolio\Models\Project', 'taggable');
    }



    protected static function boot() {
        parent::boot();

        // When deleting a tag we should also clean up any relationships
        static::deleting(function($tag) {
             $tag->projects()->detach();
        });

        // When saving, the slug is always a sluggified version of the title
        static::saving(function($tag) {
             $tag->slug = Str::slug($tag->title);
        });
    }

}
