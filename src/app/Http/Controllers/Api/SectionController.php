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
    	return $this->restfulRepository->index(new Section);
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
        return $this->restfulRepository->show(new Section, $id);
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
        return $this->restfulRepository->store(new Section, $request);
	}


    /**
     * Update Project
     *
     * @returns Illuminate response (JSON & HTTP code)
     */
    public function update($id, Request $request)
	{
        return $this->restfulRepository->update(new Section, $id, $request);
	}


    /**
     * Destroy Project
     *
     * Return Illuminate response (JSON & HTTP code)
     */
    public function destroy($id)
	{
        return $this->restfulRepository->destroy(new Project, $id);
	}


}
