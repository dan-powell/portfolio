<?php namespace DanPowell\Portfolio\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Load up the models
use DanPowell\Portfolio\Models\Project;
use DanPowell\Portfolio\Models\Section;


class ProjectSectionController extends Controller {


    /**
     * Index
     *
     * Return Illuminate response (JSON list of projects)
     */
	public function index($project_id)
	{
    	$project = new Project;

    	$section = Section::where('attachment_id', '=', $project_id)->where('attachment_type', '=', get_class($project))->orderBy('rank', 'DESC')->get();

    	return response()->json($section);
	}


    /**
     * Show
     */
    public function show($project_id, $section_id)
	{
        // Find the item by ID
        $section = Section::find($section_id);

        if (!$section) {

            // Fail - Return an error if not
            return response()->json(['errors' => ['Item not found, perhaps it was deleted?']], 422);
        } else {

            // Success - Return project as JSON object
    	    return response()->json($section);
    	}
	}


    /**
     * Store
     *
     * Return Illuminate response (JSON & HTTP code)
     */
    public function store(Request $request)
	{

    	// Return errors as JSON if request does not validate against model rules
        $this->validate($request, Section::$rules);

        $section = new Project();

        // Update the item with request data
        $section->fill($request->all());

        // Check if the data saved OK
        if (!$section->save()) {

            // Fail - Return error as JSON
            return response()->json(['errors' => ['Error updating DB entry']], 422);
        } else {

            // Success - Return item ID as JSON
            return response()->json(['id' => $section->id], 200);
        }

	}


    /**
     * Create Project
     */
    public function create()
	{
        // Do nuffin'
	}


    /**
     * Edit Project
     *
     * Return Illuminate response (JSON & HTTP code)
     */
    public function edit($id)
	{
        // Do nuffin'
	}


    /**
     * Update Project
     *
     * Return Illuminate response (JSON & HTTP code)
     */
    public function update($project_id, $section_id, Request $request)
	{
        // Return errors as JSON if request does not validate against model rules
        $this->validate($request, Section::$rules);

        // Find the item to update
        $section = Section::find($section_id);

        // Check if the item exists
        if (!$section) {

            // Return an error if not
            return response()->json(['errors' => ['Item not found, perhaps it was deleted?']], 422);
        } else {

            // Update the item with request data
            $section->fill($request->all());

            // Check if the data saved OK
            if (!$section->save()) {

                // Fail - Return error as JSON
                return response()->json(['errors' => ['Error updating DB entry']], 422);
            } else {

                // Success - Return item ID as JSON
                return response()->json(['id' => $section->id], 200);
            }

        }
	}


    /**
     * Destroy Project
     *
     * Return Illuminate response (JSON & HTTP code)
     */
    public function destroy($project_id, $section_id)
	{

        // Find the item by ID
        $section = Section::find($id);

        if (!$section) {

            // Fail - Return an error if not
            return response()->json(['errors' => ['Item not found, perhaps it was deleted?']], 422);
        } else {

            if (!$section->delete()) {

                // Fail - Return error as JSON
                return response()->json(['errors' => ['Error removing DB entry']], 422);
            } else {

                // Success - Return item ID as JSON
                return response()->json(['id' => $section->id], 200);
            }

    	}

	}


}
