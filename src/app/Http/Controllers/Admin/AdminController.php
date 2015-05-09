<?php namespace DanPowell\Portfolio\Http\Controllers\Admin;


use Illuminate\Routing\Controller;

class AdminController extends Controller {

    /**
    *   Return a view listing all of the projects
	*
	*/
	public function index()
	{
        return view('portfolio::admin.dashboard');
	}


}
