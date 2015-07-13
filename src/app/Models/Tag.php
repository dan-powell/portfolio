<?php namespace DanPowell\Portfolio\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

    protected $fillable = [
        'title'
    ];

    public function rules($id = null)
	{
	    return [
    	    'title' => 'required|unique:tags,title,' . $id
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
    }

}
