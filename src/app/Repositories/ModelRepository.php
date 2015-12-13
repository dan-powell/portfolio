<?php namespace DanPowell\Portfolio\Repositories;

/*
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
*/

use Illuminate\Database\Eloquent\Model;


/**
 * A handy repo for doing common RESTful based things like indexing, saving etc.
 */
class ModelRepository
{


    // Get all Things
    public function getAll(Model $model, $with = [], $order = 'created_at', $by = 'DESC')
	{
    	return $model::with($with)->orderBy($order, $by)->get();
    }


    // Loop through all of the projects and concatenate the tags together as a single string - keeps the template clean
    public function addAllTagstoCollection($collection)
	{
        foreach($collection as $item) {
            $item->allTags = $this->collateTagsAsString($item);
    	}
        return $collection;
	}


    // Get all project tags as string
    private function collateTagsAsString($item)
    {
    	$tags = '';
        foreach($item->tags as $tag){
    	    $tags .= '-' . str_slug($tag->title) . ' ';
        }
        return $tags;
    }


    // Get all tags & filter so only those related to project are returned
    public function filterOnlyWithRelationship($collection, $related)
    {

        // Use Eloquent's filter method, returning only items that have a relationship with $related
        $collection = $collection->filter(function($item) use ($related)
        {
            if(isset($item->$related) && count($item->$related) > 0) {
        	    return $item;
        	}
        });

        return $collection;
    }


}