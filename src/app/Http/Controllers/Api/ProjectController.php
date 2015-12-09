<?php namespace DanPowell\Portfolio\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DanPowell\Portfolio\Repositories\Api\ProjectRepository;

// Load up the models
use DanPowell\Portfolio\Models\Project;


class ProjectController extends Controller {


    /**
     * RESTful Repository
     * @var Repository
     */
    protected $projectRepository;

    /**
     * Inject the repos
     * @param RestfulRepository $restfulRepository
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        // Make sure oly authorised users can post/put. 'Get' does not require authorisation.
        $this->middleware('auth', ['except' => ['index','show']]);
        $this->projectRepository = $projectRepository;
    }


    /**
     * Index Projects
     *
     * @returns Illuminate response (JSON list of projects)
     */
	public function index()
	{
    	return $this->projectRepository->index(new Project);
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
        return $this->projectRepository->show(new Project, $id, ['sections', 'tags', 'pages']);
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
        return $this->projectRepository->store(new Project, $request);
	}


    /**
     * Update Project
     *
     * @returns Illuminate response (JSON & HTTP code)
     */
    public function update($id, Request $request)
	{
        return $this->projectRepository->update(new Project, $id, $request);
	}


    /**
     * Destroy Project
     *
     * Return Illuminate response (JSON & HTTP code)
     */
    public function destroy($id)
	{
        return $this->projectRepository->destroy(new Project, $id);
	}


}
