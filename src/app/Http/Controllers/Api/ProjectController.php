<?php namespace DanPowell\Portfolio\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Validator;

// Load up the models
use DanPowell\Portfolio\Models\Project;
use DanPowell\Portfolio\Models\Section;
use DanPowell\Portfolio\Models\Page;
use DanPowell\Portfolio\Models\Tag;


class ProjectController extends Controller {


    /**
     * Index Projects
     *
     * Return Illuminate response (JSON list of projects)
     */
	public function index()
	{
    	$projects = Project::orderBy('created_at', 'DESC')->get();

    	return response()->json($projects);

	}


    /**
     * Store Project
     *
     * Create new project entry
     *
     * Return Illuminate response (JSON & HTTP code)
     */
    public function store(Request $request)
	{

    	// Return errors as JSON if request does not validate against model rules
        $this->validate($request, Project::$rules);

        $project = new Project();


        // Update the item with request data
        $project->fill($request->all());

        // Slugify the slug
        $project->slug = Str::slug($request->get('slug'));


        // Check if the data saved OK
        if (!$project->save()) {

            // Fail - Return error as JSON
            return response()->json(['errors' => ['Error updating DB entry']], 422);
        } else {

            // Success - Return item ID as JSON
            return response()->json(['id' => $project->id], 200);
        }

	}


    /**
     * Edit Project
     *
     * Find & return project by ID
     *
     * Return Illuminate response (JSON & HTTP code)
     */
    public function edit($id)
	{
    	// Find the item by ID
        $project = Project::with(['sections', 'tags', 'pages'])->find($id);

        if (!$project) {

            // Fail - Return an error if not
            return response()->json(['errors' => ['Item not found, perhaps it was deleted?']], 422);
        } else {

            // Success - Return project as JSON object
    	    return response()->json($project);
    	}
	}


    /**
     * Update Project
     *
     * Return Illuminate response (JSON & HTTP code)
     */
    public function update($id, Request $request)
	{

        // Return errors as JSON if request does not validate against model rules
        $this->validate($request, Project::$rules);

        // Find the item to update
        $project = Project::find($id);

        // Check if the item exists
        if (!$project) {

            // Return an error if not
            return response()->json(['errors' => ['Item not found, perhaps it was deleted?']], 422);
        } else {

            // Update the item with request data
            $project->fill($request->all());

            // Slugify the slug
            $project->slug = Str::slug($request->get('slug'));

            // Check if the data saved OK
            if (!$project->save()) {

                // Fail - Return error as JSON
                return response()->json(['errors' => ['Error updating DB entry']], 422);
            } else {

                if ($request->get('sections')) {

                    $errors = [];

                    foreach($request->get('sections') as $sectionData) {

                        $v = Validator::make($sectionData, Section::$rules);

                        if ($v->fails()) {

                            //array_push($errors, $v->errors());
                            return response()->json($v->errors(), 422);
                        }

                    }


                    Section::where('attachment_id', '=', $project->id)
                        ->where('attachment_type', '=', get_class($project))
                        ->delete();


                    foreach($request->get('sections') as $sectionData) {






                        $section = new Section($sectionData);

                        $project->sections()->save($section);

                    }


                }



                // Success - Return item ID as JSON
                return response()->json(['id' => $project->id], 200);
            }

        }
	}


    /**
     * Destroy Project
     *
     * Return Illuminate response (JSON & HTTP code)
     */
    public function destroy($id)
	{

        // Find the item by ID
        $project = Project::find($id);

        if (!$project) {

            // Fail - Return an error if not
            return response()->json(['errors' => ['Item not found, perhaps it was deleted?']], 422);
        } else {

            if (!$project->delete()) {

                // Fail - Return error as JSON
                return response()->json(['errors' => ['Error removing DB entry']], 422);
            } else {

                // Success - Return item ID as JSON
                return response()->json(['id' => $project->id], 200);
            }

    	}

	}


}
