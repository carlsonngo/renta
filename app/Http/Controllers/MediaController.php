<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config, File, Response, Image;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $user;
    protected $usermeta;
    protected $post;
    protected $postmeta;
    protected $setting;
    protected $request;


    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Request $request)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->setting  = $setting;
        $this->request  = $request;

        $this->view      = 'backend.media';
        $this->label     = trans('backend.media_library');

        $this->middleware(function ($request, $next) {
            $this->site_id = get_domain();
            $this->user_id = Auth::check() ? Auth::user()->id : 0;
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function index()
    {	
	    $data['files']  = media_library('uploads/'.$this->site_id.'/');
        $data['view']   = $this->view;                                      
        $data['label']  = $this->label; 
        $data['module'] = 'media';

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function frame()
    {   
        $data['files'] = media_library('uploads/'.$this->site_id.'/');
        $data['view']  = $this->view;                                      
        $data['label'] = $this->label; 

        return view($this->view.'.frame', $data);
    }

    //--------------------------------------------------------------------------

    public function frame_add()
    {   
        $data['view']    = $this->view;                                      
        $data['label']   = $this->label; 

        return view($this->view.'.frame-add', $data);
    }

    //--------------------------------------------------------------------------
            
    public function add()
    {	
        $data['view']    = $this->view;                                      
        $data['label']   = $this->label; 

        return view($this->view.'.add', $data);
    }

    //--------------------------------------------------------------------------
    
    public function upload()
    {	

        $input = Input::all();

        $rules = array(
            'file' => 'max:999999999',
        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails())
        {
            return Response::make($validation->errors->first(), 400);
        }

        $file = Input::file('file');

        $imageFile  = $file->getRealPath();
        $format     = get_file_format($file->getClientMimeType());
        $ext        = $file->getClientOriginalExtension();
        $name       = str_replace([' ', '-'], '_', ucwords( pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ));
        
        $path_parent = 'uploads/'.$this->site_id;  
        $path        = $path_parent.'/'.$format.'/';  

        $string     = $name.'-'.strtolower(str_random(8));

        if( ! file_exists($path_parent) )  mkdir( $path_parent );  
        if( ! file_exists($path) )  mkdir( $path );           

        if(  $format != 'image' ) {

        	$ext = $ext ? '.'.$ext : '';
            $file_name   = $path.$string.'-'.$format.$ext;

            move_uploaded_file($imageFile, $file_name); 

        } else {

            $thumbnail_path   = $path.'/'.$string.'-thumb.'.$ext;
            $upload_success = \Image::make($imageFile)->resize(150, '', function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->fit(150, 150)->save($thumbnail_path);
            compress($thumbnail_path, $thumbnail_path, 70);

            /* Medium */
            $medium_path   = $path.'/'.$string.'-medium.'.$ext;
            \Image::make($imageFile)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->fit(300, 300)->save($medium_path);
            // compress($medium_path, $medium_path, 70);

            /* Large */
            $large_path   = $path.'/'.$string.'-large.'.$ext;
            \Image::make($imageFile)->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($large_path);

            $original_path   = $path.'/'.$string.'.'.$ext;
            move_uploaded_file($imageFile, $original_path); 
        }

        $data['filename'] = $string;

        return Response::json($data, 200);

    }

    //--------------------------------------------------------------------------

    public function unlink()
    {
        $folder   = Input::get('folder');
        $filename = Input::get('filename');

	    $dir = 'uploads/'.$this->site_id.'/'.$folder.'/';

        foreach (glob($dir.'*-'.$filename."-*") as $file) {
           unlink($file);
        }

        $t_file = trans('backend.file');

        return Redirect::route($this->view.'.index', query_vars('folder=0&filename=0'))
                           ->with('success', trans('messages.deleted', ['variable' => strtolower($t_file)]));
    }

    //--------------------------------------------------------------------------
}
