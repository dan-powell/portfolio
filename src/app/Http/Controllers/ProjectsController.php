<?php namespace DanPowell\Portfolio\Http\Controllers;


use Illuminate\Routing\Controller;

// Load up the models
use DanPowell\Portfolio\Models\Project;
use DanPowell\Portfolio\Models\Page;
use DanPowell\Portfolio\Models\Tag;


class ProjectsController extends Controller {

    /**
    *   Return a view listing all of the projects
	*
	*/
	public function index()
	{
    	// Get all the projects
    	$projects = Project::with('tags')->orderBy('created_at', 'DESC')->get();

        // Loop through all of the projects and concatenate the tags together as a single string - keeps the template clean
    	foreach($projects as $project) {
        	$collectTags = '';
            foreach($project->tags as $tag){
        	    $collectTags .= '-' . str_slug($tag->title) . ' ';
            }
        	$project->allTags = $collectTags;
    	}

        // Get all the tags
    	$tags = Tag::with('projects')->orderBy('created_at', 'DESC')->get();

    	// We only need tags that have a relationship with a project
        // Use Eloquent's filter method, allowing only tags with relationships to Projects are be returned
        $tagsFiltered = $tags->filter(function($tag)
        {
            if(isset($tag->projects) && count($tag->projects) > 0) {
        	    return $tag;
        	}
        });

        // Return view along with projects and filterestags
		return view('portfolio::index')->with(['projects' => $projects, 'tags' => $tagsFiltered]);
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
