<?php namespace DanPowell\Portfolio\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Storage;
use File;
//use Symfony\Component\HttpFoundation\File\UploadedFile;
//use Illuminate\Filesystem\Filesystem;

// Load up the models
use DanPowell\Portfolio\Models\Project;

class AssetController extends Controller {


    public function __construct()
    {
        // Make sure only authorised users can Create Udate & Delete. 'Read' does not require authorisation.
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if($path = $request->get('folder')){

    	    $storage = Storage::disk('portfolio')->files($path);
    	    if($storage){

        	    $files = [];
        	    foreach($storage as $file) {

            	    $object = [];

            	    $object['path'] = $file;
            	    $object['extension'] = substr(strrchr($file, '.'), 1);
            	    $object['filename'] = basename($file);
                    $object['size'] = Storage::disk('portfolio')->size($file);
                    $object['lastmodified'] = Storage::disk('portfolio')->lastModified($file);

            	    $files[] = $object;
        	    }

                $folders = $this->getDirectories('portfolio', $path);

                return response()->json(['files' => $files, 'folders' => $folders], 200);


    	    } else {
        	    dd('HONK');
    	    }

        }
    }


    public function store(Request $request)
    {
        $file = $request->file('file');

        $path = $request->get('path');
        $name = $file->getClientOriginalName();
		$type = $file->getClientMimeType();

		Storage::disk('portfolio')->put($path . '/' . $name,  File::get($file));
    }



    protected function getDirectories($disk, $directory)
    {

        $folders = Storage::disk($disk)->directories($directory);

        $test = [];
        if(count($folders) > 0){


            foreach($folders as $folder) {
                $object = [];

                $object['path'] = $folder;
                $object['name'] =  substr(strrchr($folder, '/'), 1);
                $object['folders'] = $this->getDirectories($disk, $folder);

                $test[] = $object;

            }
        }

        return $test;

    }


}
