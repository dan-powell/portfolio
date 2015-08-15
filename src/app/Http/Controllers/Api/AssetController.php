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


    private $disk = 'portfolio';

    private $validation = [
        'file_regex' => '/^([a-zA-Z0-9\-\_\/\.\(\)\?\!])+\.+([a-zA-Z0-9])+/', // '/^([a-zA-Z0-9\-\_\/\.\(\)\?\!])+$/'
        'path_regex' => '/^([a-zA-Z0-9\-\_\/\(\)])+$/',
        'file_mime' => 'jpeg,bmp,png,pdf,gif,svg,css,js,ai,eps,html,zip,doc,xls,md,csv,xml,rtf,txt'
    ];

    public function __construct()
    {
        // Only authorised access
        //$this->middleware('auth');
    }


    public function index(Request $request)
    {

        // Look in the root if a path is not defined
        if(!$path = $request->get('path')){;
            $path = "";
        }

        // Check if the asset exists
        if(Storage::disk($this->disk)->exists($path)) {
            // Return files and subfolders
            return response()->json([
                'files' => $this->getFiles($this->disk, $path),
                'folders' => $this->getDirectories($this->disk, $path)
            ], 200);
        } else {
            // Asset is missing - return error
            return response()->json(['errors' => 'Asset not found'], 422);
        }

    }


    public function store(Request $request)
    {

        // Validate the request
        $this->validate($request, [
            'path' => 'regex:' . $this->validation['path_regex'],
            'file' => 'mimes:' . $this->validation['file_mime'],
            'filename' => 'required_with_all:file|regex:' . $this->validation['file_regex'],
        ]);

        // Get the folder (if there is one)
        $path = $request->get('path');

        // Check if a file is present
        if ($request->hasFile('file') && $file = $request->file('file')) {

            // concat the path & filename
            $put = $path . '/' . $request->get('filename');

            // Does a file already exist?
            if(Storage::disk($this->disk)->exists($put)) {
                return response()->json(['errors' => 'File already exists'], 422);
            } else {
                // File doesn't exist - Save it!
                Storage::disk($this->disk)->put($put, File::get($file));
                return response()->json(['file' => $put], 200);
            }

        } else {
            // No file - Create a folder

            // Check if folder exists
            if(Storage::disk($this->disk)->exists($path)) {
                return response()->json(['errors' => 'Folder already exists'], 422);
            } else {
                // Folder doesn't already exist - create it!
                Storage::disk($this->disk)->makeDirectory($path);
                return response()->json(['folder' => $path], 200);
            }
        }
    }


    public function update(Request $request)
    {

        $errors = [];

        $target = $request->get('target');

        if (!$target) {
            $errors[] = 'Target file/path missing';
        }

        $destination = $request->get('destination');

        if (!$destination) {
            $errors[] = 'Destination file/path missing';
        }



    	$file = Storage::disk($this->disk)->exists($target);

    	if ($file) {

        	$storage = Storage::disk($this->disk)->move($target, $destination);

        	return response()->json([$storage], 200);

    	} else {




    	}

    }



    public function destroy(Request $request)
    {

        $errors = [];

        $path = $request->get('path');

        if (!$path) {
            $errors[] = 'Target file/path missing';
        }

    	$files = Storage::disk($this->disk)->files($path);

    	if ($files) {

            Storage::disk($this->disk)->deleteDirectory($path);

        	return response()->json(['folder delete'], 200);

    	} else {

            $storage = Storage::disk($this->disk)->delete($path);

        	return response()->json(['file delete'], 200);


    	}

    }









    protected function getFiles($disk, $path)
    {

        $files = Storage::disk($disk)->files($path);

        $array = [];
        foreach($files as $file) {
    	    $object = [];
    	    $object['path'] = $file;
    	    $object['extension'] = substr(strrchr($file, '.'), 1);
    	    $object['filename'] = basename($file);
            $object['size'] = Storage::disk($disk)->size($file);
            $object['lastmodified'] = Storage::disk($disk)->lastModified($file);

    	    $array[] = $object;
	    }

	    return $array;
    }


    protected function getDirectories($disk, $path)
    {

        $folders = Storage::disk($disk)->directories($path);

        $array = [];
        foreach($folders as $folder) {
            $object = [];
            $object['path'] = $folder;
            $object['name'] =  substr(strrchr($folder, '/'), 1);
            $object['folders'] = $this->getDirectories($disk, $folder);

            $array[] = $object;
        }

        return $array;
    }


}
