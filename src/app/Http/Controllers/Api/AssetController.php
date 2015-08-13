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


    public function __construct()
    {
        // Only authorised access
        //$this->middleware('auth');
    }


    public function index(Request $request)
    {

        if(!$path = $request->get('path')){;
            $path = "";
        }

        return response()->json([
            'files' => $this->getFiles($this->disk, $path),
            'folders' => $this->getDirectories($this->disk, $path)
        ], 200);

    }


    public function store(Request $request)
    {

        $errors = [];

        if (!$path = $request->get('path')) {
            $errors[] = 'Path missing';
        }


        if (!$name = $request->get('name')) {
            $errors[] = 'Name missing';
        }


        if(count($errors) > 0) {
            return response()->json(['errors' => $errors], 422);
        }

        $put = $path . '/' . $name;

        if ($file = $request->file('file')) {

            $storage = Storage::disk($this->disk)->put($put, File::get($file));

            if($storage) {
                return response()->json(['file' => $put], 200);
            } else {
                return response()->json(['errors' => $storage], 422);
            }

        } else {

            $storage = Storage::disk($this->disk)->makeDirectory($put);

            if($storage) {
                return response()->json(['folder' => $put], 200);
            } else {
                return response()->json(['errors' => $storage], 422);
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
