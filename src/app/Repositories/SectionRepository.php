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

use DanPowell\Portfolio\Models\Section;

/**
 * A handy repo for doing common RESTful based things like indexing, saving etc.
 */
class SectionRepository extends RestfulRepository
{
    /**
     * Save a new record of model
     *
     * @param $class Class of model to save data as (Eloquent Model)
     * @param $request data to save to model (Illuminate Request)
     *
     * @return data collection of newly saved record as JSON response (Http Response)
     */
    public function storeSection($class, $id, $request)
    {
        // Modify some of the input data
        $this->modifyRequestData($request);

        $section = new Section;

        // Return errors as JSON if request does not validate against model rules
        $v = Validator::make($request->all(), $section->rules());

        if ($v->fails())
        {
            return response()->json($v->errors(), 422);
        }


        $collection = $class::find($id);


        // Update the item with request data
        $section->fill($request->all());


        // Check if the data saved OK
        if (!$collection->sections()->save($section)) {

            // Fail - Return error as JSON
            return response()->json(['errors' => [$this->messages['error_updating']]], 422);
        } else {

            // Success - Return item ID as JSON
            return response()->json($section, 200);
        }
    }
}