<?php namespace DanPowell\Portfolio\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DanPowell\Portfolio\Repositories\PageRepository;

// Load up the models
use DanPowell\Portfolio\Models\Page;


class PageController extends Controller {


    /**
     * RESTful Repository
     * @var Repository
     */
    protected $pageRepository;

    /**
     * Inject the repos
     * @param RestfulRepository $restfulRepository
     */
    public function __construct(PageRepository $pageRepository)
    {
        // Make sure oly authorised users can post/put. 'Get' does not require authorisation.
        $this->middleware('auth', ['except' => ['index','show']]);
        $this->pageRepository = $pageRepository;
    }


    /**
     * Index Projects
     *
     * @returns Illuminate response (JSON list of projects)
     */
	public function index()
	{
    	return $this->pageRepository->index(new Page);
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
        return $this->pageRepository->show(new Page, $id, ['sections', 'attachment']);
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
        return $this->pageRepository->store(new Page, $request);
	}


    /**
     * Update Section
     *
     * @returns Illuminate response (JSON & HTTP code)
     */
    public function update($id, Request $request)
	{
        return $this->pageRepository->update(new Page, $id, $request);
	}


    /**
     * Destroy Section
     *
     * Return Illuminate response (JSON & HTTP code)
     */
    public function destroy($id)
	{
        return $this->pageRepository->destroy(new Page, $id);
	}


}
