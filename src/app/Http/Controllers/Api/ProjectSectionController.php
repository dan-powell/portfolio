<?php namespace DanPowell\Portfolio\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DanPowell\Portfolio\Repositories\RestfulRepository;

// Load up the models
use DanPowell\Portfolio\Models\Section;
use DanPowell\Portfolio\Models\Project;

class ProjectSectionController extends Controller {

    /**
     * RESTful Repository
     * @var Repository
     */
    protected $restfulRepository;

    /**
     * Inject the repos
     * @param ClueRepository $clueRepo
     * @param TagRepository $tagRepo
     */
    public function __construct(RestfulRepository $restfulRepository)
    {
        $this->restfulRepository = $restfulRepository;
    }


    public function index($project_id)
    {
    	return $this->restfulRepository->indexRelated(new Section, $project_id, new Project);
    }

}
