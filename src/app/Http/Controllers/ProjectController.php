<?php namespace DanPowell\Portfolio\Http\Controllers;


use Illuminate\Routing\Controller;

// Load up the models
use DanPowell\Portfolio\Models\Project;
use DanPowell\Portfolio\Models\Page;
use DanPowell\Portfolio\Models\Tag;

use DanPowell\Portfolio\Repositories\ProjectRepository;

class ProjectController extends Controller
{

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
    *   Return a view listing all of the projects
	*
	*   @return View - returns created page, or throws a 404 if slug is invalid or can't find a matching record
	*/
	public function index()
	{

    	// Get all the projects
    	$projects = $this->projectRepository->getAllProjects('tags', 'created_at');

    	// Add tags to projects as string
    	$projects = $this->projectRepository->addAllTagstoCollection($projects);

    	// Get all tags
    	$tags = $this->projectRepository->getAllTags('projects', 'created_at');

    	// Filter only by those with relationship to a project
    	$tags = $this->projectRepository->filterProjectTagsWithRelationship($tags, 'projects');

        // Return view along with projects and filtered tags
		return view('portfolio::index')->with([
		    'projects' => $projects,
		    'tags' =>  $tags
        ]);
	}


	/**
    *   Return a view showing one of the projects
	*
	* @param String $slug - if numeric will be treated as an id, otherwise will search for matching slug
	* @return View - returns created page, or throws a 404 if slug is invalid or can't find a matching record
	*/
	public function show($slug)
	{
        // check to see if id is valid then determine if it is an id or a slug
        if (is_numeric($slug)) {
            // If a number is supplied, use that to find project by ID
            $project = Project::find($slug);

            // Check if a project was found
            if ($project != null) {
                // Project found OK, return a 301 redirect to the correct slug
            	return redirect()->route('projects.show', $project->slug, 301);
            } else {
                // No project found, throw a 404.
	            return abort('404', 'Invalid project id');
	        }
        }
        else {
	        $query = Project::where('slug', '=', $slug);
	        $project = $query->first();

            // Check if a project was found
	        if ($project != null) {

                // Parse the body text in to sections
                //$project->sections = $this->parseSections($project->content, ['section', 'container']);

                // Set the default template if not provided
		        if ($project->template == null || $project->template == 'default') {
			        $template = 'portfolio::show';
			    } else {
				    $template = 'portfolio::templates.' . $project->template;
				}

                // Return view with projects
				return view($template)->with(['project' => $project]);

		   	} else {
    		   	// No project found, throw a 404.
	            return abort('404', 'Invalid project slug');
	        }
        }
	}


    /**
    *   Return a view showing one of the pages
	*
	* @param String $slug - if numeric will be treated as an id, otherwise will search for matching slug
	* @param String $pageSlug - if numeric will be treated as an id, otherwise will search for matching slug
	* @return View - returns created page, or throws a 404 if slug is invalid or can't find a matching record
	*/
	public function page($slug, $pageSlug)
	{
    	// Build query to find relevent Project
        $query = Project::where('slug', '=', $slug)->with('pages');
        $project = $query->first();

        // Check if a project was found
        if ($project == null) {
            return abort('404', 'Invalid project slug');
        } else {

            // Filter related pages and return the one with the correct slug
            $filteredPages = $project->pages->filter(function($page) use ($pageSlug)
            {
                if(isset($page->slug) && $page->slug == $pageSlug) {
            	    return $page;
            	}
            });
            $page = $filteredPages->first();

            // Check if a page was found
            if ($page != null) {

                if ($page->template == null || $page->template == 'default') {
    		        $template = 'portfolio::page';
    		    } else {
    			    $template = 'portfolio::pages.' . $page->template;
    			}

                // Return view with projects
    			return view($template)->with(['page' => $page, 'project' => $project]);

    	   	} else {
    		   	// No project found, throw a 404.
                return abort('404', 'Invalid page slug');
            }
        }

	}


}
