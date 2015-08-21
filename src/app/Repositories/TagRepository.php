<?php namespace DanPowell\Portfolio\Repositories;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

/*
// Load up the models
use DanPowell\Portfolio\Models\Project;
use DanPowell\Portfolio\Models\Section;
use DanPowell\Portfolio\Models\Page;
use DanPowell\Portfolio\Models\Tag;
*/

use DanPowell\Portfolio\Models\Tag;

//use DanPowell\Portfolio\Models\Section;

/**
 * A handy repo for doing common RESTful based things like indexing, saving etc.
 */
class TagRepository extends RestfulRepository
{



	/**
     * Search Projects
     *
     * @returns Illuminate response (JSON list of projects)
     */
	public function search($request)
	{
		
		$term = '%' . $request->get('query') . '%';

        $collection = Tag::orderBy('updated_at', 'DESC')->where('title', 'LIKE', $term)->get();

    	return response()->json($collection);

    	
	}




    /**
     * Save a new record of model
     *
     * @param $class Class of model to save data as (Eloquent Model)
     * @param $request data to save to model (Illuminate Request)
     *
     * @return data collection of newly saved record as JSON response (Http Response)
     */
    public function store($class, $request)
    {
        // Modify some of the input data
        if (!$request->get('slug')) {
            $request->merge(['slug' => Str::slug($request->get('slug'))]);
        }

        // Return errors as JSON if request does not validate against model rules
        $v = Validator::make($request->all(), $class->rules());

        if ($v->fails())
        {
            return response()->json($v->errors(), 422);
        }

        // Update the item with request data
        $class->fill($request->all());

        // Check if the data saved OK
        if (!$class->save()) {

            // Fail - Return error as JSON
            return response()->json(['errors' => [$this->messages['error_updating']]], 422);
        } else {

            // Success - Return item ID as JSON
            return response()->json($class, 200);
        }
    }



}