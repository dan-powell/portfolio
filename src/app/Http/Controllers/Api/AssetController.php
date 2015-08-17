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
                'folder' => $this->getFolderInfo($this->disk, $path)
            ], 200);
        } else {
            // Asset is missing - return error
            return response()->json(['errors' => 'Asset not found'], 404);
        }

    }


    public function store(Request $request)
    {

        // Validate the request
        $this->validate($request, [
            'path' => 'required_without_all:file|regex:' . $this->validation['path_regex'],
            'file' => 'required_without_all:path|mimes:' . $this->validation['file_mime'],
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
                return response()->json(['file' => $this->getFileInfo($this->disk, $put)], 200);
            }

        } else {
            // No file - Create a folder

            // Check if folder exists
            if(Storage::disk($this->disk)->exists($path)) {
                return response()->json(['errors' => 'Folder already exists'], 422);
            } else {
                // Folder doesn't already exist - create it!
                Storage::disk($this->disk)->makeDirectory($path);
                return response()->json(['folder' => $this->getFolderInfo($this->disk, $path)], 200);
            }
        }
    }


    public function update(Request $request)
    {

        // Validate the request
        $this->validate($request, [
            'src_path' => 'required_without_all:src_file',
            'src_file' => 'required_without_all:src_path',
            'dest_path' => 'required_without_all:src_file|required_with_all:src_path|regex:' . $this->validation['path_regex'],
            'dest_file' => 'required_with_all:src_file|regex:' . $this->validation['file_regex'],
        ]);

        $source = rtrim($request->get('src_path'), '/') . '/' . $request->get('src_file');

        // Check if the src/target file/folder exists
    	if (Storage::disk($this->disk)->exists($source)) {

            $destination = rtrim($request->get('dest_path'), '/') . '/' . $request->get('dest_file');

            // Check if the destination file/folder exists
            if (Storage::disk($this->disk)->exists($destination)) {
                return response()->json(['errors' => 'Destination file/folder already exists'], 422);

            } else {

                // Move the file folder & check if moved OK
                if($storage = Storage::disk($this->disk)->move($source, $destination)) {

                    $array = [
                        'src' => $source,
                        'dest' => $destination
                    ];

                    if ($request->get('src_file')) {
                        $array['file'] = $this->getFileInfo($this->disk, $destination);
                    } else {
                        $array['folder'] = $this->getFolderInfo($this->disk, $destination);
                    }

                    return response()->json($array, 200);
                } else {
                    return response()->json(['errors' => 'Asset update failed'], 422);
                }

            }

    	} else {
            // Asset deos not exist - return error message
            return response()->json(['errors' => 'Source file/folder does not exist'], 422);
    	}

    }



    public function destroy(Request $request)
    {

        // Validate the request
        $this->validate($request, [
            'path' => 'required_without_all:file',
            'file' => 'required_without_all:path'
        ]);


        $source = $request->get('path') . '/' . $request->get('file');

        // Check if the src/target file/folder exists
        if (Storage::disk($this->disk)->exists($source)) {

            // Are we dealing with a file or a folder?
            if($request->get('file')) {

                // Attempt to delete the file & check if deleted OK
                if($storage = Storage::disk($this->disk)->delete($source)) {
                    return response()->json(['file' => $source], 200);
                } else {
                    return response()->json(['errors' => 'File deletion failed'], 422);
                }

            } else {

                // Attempt to delete the folder & check if deleted OK
                if(Storage::disk($this->disk)->deleteDirectory($source)) {
                    return response()->json(['folder' => $source], 200);
                } else {
                    return response()->json(['errors' => 'Folder deletion failed'], 422);
                }

            }

    	} else {
            // Asset deos not exist - return error message
            return response()->json(['errors' => 'File/folder does not exist'], 422);
    	}

    }


    private function getFiles($disk, $path)
    {

        $files = Storage::disk($disk)->files($path);

        $array = [];
        foreach($files as $file) {
    	    $array[] = $this->getFileInfo($disk, $file);
	    }

	    return $array;
    }


    private function getFileInfo($disk, $path)
    {

	    $array = [];
	    $array['path'] = rtrim($path, basename($path));
	    $array['filename'] = basename($path);
	    $array['extension'] = substr(strrchr($path, '.'), 1);
        $array['size'] = Storage::disk($disk)->size($path);
        $array['lastmodified'] = Storage::disk($disk)->lastModified($path);

	    return $array;
    }


    private function getDirectories($disk, $path)
    {

        $folders = Storage::disk($disk)->directories($path);

        $array = [];
        foreach($folders as $folder) {
            $array[] = $this->getFolderInfo($disk, $folder, $path);
        }

        return $array;
    }


    private function getFolderInfo($disk, $path)
    {

        $array = [];
        $array['name'] =  $this->getFolderName($path);
        $array['path'] = rtrim(rtrim($path, $this->getFolderName($path)), '/');
        $array['folders'] = $this->getDirectories($disk, $path);

	    return $array;
    }


    private function getFolderName($path)
    {

        // Trim any trailing slashes
        $path = rtrim($path, '/');

        if (strpos($path,'/') !== false) {
            return substr(strrchr($path, '/'), 1);
        } else {
            return $path;
        }

    }

}
