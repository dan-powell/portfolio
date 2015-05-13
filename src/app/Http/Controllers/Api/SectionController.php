<?php namespace DanPowell\Portfolio\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DanPowell\Portfolio\Repositories\RestfulRepository;

// Load up the models
use DanPowell\Portfolio\Models\Section;


class SectionController extends Controller {


    /**
     * RESTful Repository
     * @var Repository
     */
    protected $restfulRepository;

    /**
     * Inject the repos
     * @param RestfulRepository $restfulRepository
     */
    public function __construct(RestfulRepository $restfulRepository)
    {
        $this->restfulRepository = $restfulRepository;
    }


    /**
     * Index Projects
     *
     * @returns Illuminate response (JSON list of projects)
     */
	public function index()
	{
        $project = new Section;
    	return $this->restfulRepository->index($project);
	}


	/**
     * Show Project
     *
     * Find & return project by ID
     *
     * @returns Illuminate response (JSON & HTTP code)
     */
    public function show($id)
	{
        $project = new Section;
        return $this->restfulRepository->show($project, $id);
	}


    /**
     * Store Project
     *
     * Create new project entry
     *
     * @returns Illuminate response (JSON & HTTP code)
     */
    public function store(Request $request)
	{
        $project = new Section;
        return $this->restfulRepository->store($project, $request);
	}


    /**
     * Update Project
     *
     * @returns Illuminate response (JSON & HTTP code)
     */
    public function update($id, Request $request)
	{
        $project = new Section;
        return $this->restfulRepository->update($project, $id, $request);
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
