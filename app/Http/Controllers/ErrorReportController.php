<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;

class ErrorReportController extends Controller
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

	    $this->view      = 'backend.error-reports';
        $this->post_type = 'error';
        $this->single    = 'Error Report';
        $this->label     = 'Error Reports';  

        $this->middleware(function ($request, $next) {
            $this->site_id = get_domain();
            $this->user_id = Auth::check() ? Auth::user()->id : 0;
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function index()
    {

        $data['single']    = $this->single;                                      
        $data['label']     = $this->label; 
        $data['view']      = $this->view;
        $data['post']      = $this->post;
        $data['post_type'] = $this->post_type;
        $data['module']    = 'error-reports';

        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->where('post_type', $this->post_type)
                             ->search($search)
                             ->orderBy(Input::get('sort', 'id'), Input::get('order', 'DESC'))
                             ->paginate(10);

        $data['count'] = $this->post
                              ->where('post_title', 'LIKE', '%'.Input::get('s').'%')
                              ->where('post_type', $this->post_type)
                              ->count();

        $data['all'] = $this->post->where('post_type', $this->post_type)->count();

        $data['trashed'] = $this->post->withTrashed()
                                      ->where('post_type', $this->post_type)
                                      ->where('deleted_at', '<>', '0000-00-00')
                                      ->count();

        /* Perform bulk actions */  
		if( Input::get('ids') ) {                                            
	        if( Input::get('action') == 'trash' ) {
	            foreach( Input::get('ids') as $id ) {
	                Post::find($id)->delete();
	            }
	            return Redirect::back()
	                           ->with('success', trans('messages.trashed', ['variable' => strtolower($this->single)]));
	        }

	        if( Input::get('action') == 'restore') {
	            foreach( Input::get('ids') as $id ) {
	                $user = Post::withTrashed()->findOrFail($id);
	                $user->restore();
	            }
	            return Redirect::back()
	                           ->with('success', trans('messages.restored', ['variable' => strtolower($this->single)]));
	        }

	        if( Input::get('action') == 'destroy') {
	            foreach( Input::get('ids') as $id ) {

	                // Remove domains
	                $data_path   = app_path('helpers/domains.php');
	                $domains     = include($data_path);
	                $domains = array_flip($domains);
	                unset($domains[$id]);
	                $domains = array_flip($domains);
	                file_put_contents($data_path, '<?php return '.var_export($domains, true).';' );  

	                PostMeta::where('post_id', $id)->delete(); 
	                $post = Post::withTrashed()->find($id);
	                $post->forceDelete();
	            }
	            return Redirect::back()
	                           ->with('success', trans('messages.destroyed', ['variable' => strtolower($this->single)]));
	        }
    	}
    	
        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public static function add($e)
    {

        $data['request'] = $request = Request();
        $data['e']       = $e;
        $data['to']      = \App\Setting::get_setting('admin_email');
        $data['subject'] = request()->server->get('SERVER_NAME').' - Error Report!';

        $title = $e->getMessage() ? $e->getMessage() : 'Unknown Error!';
        $url   = $request->getUri();

        $report = new Post();
        $report = $report->where('post_title', $title)
                         ->where('post_name', $url)
                         ->where('post_status', 'pending')
                         ->first();

        $post = $report ?? new Post();

        $today = strtotime(date('Y-m-d H:i:s', strtotime('-1 hour')));

        $post->site_id      = 0;
        $post->post_author  = 0;
        $post->post_content = view('emails.error-report', $data);               
        $post->post_title   = $title;
        $post->post_name    = $url;
        $post->post_type    = 'error';
        $post->post_order   = @$report->post_order + 1;
        $post->post_status  = 'pending';
        $post->updated_at   = date('Y-m-d H:i:s');

        if( ! $report ) {
            $post->created_at  = date('Y-m-d H:i:s');
        }

        $post->save();

        if( ! $report || $today > strtotime($report->updated_at) ) {
            \Mail::send('emails.error-report', $data, function($message) use ($data)
            {
                $message->to($data['to'])->subject($data['subject']);
            });        
        }

    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {
        $data['single']    = $this->single;                                      
        $data['label']     = $this->label; 
        $data['view']      = $this->view;
        $data['post']      = $this->post;
        $data['post_type'] = $this->post_type;
        $data['lang']      = $this->setting->get_setting('site_language');
        $data['module']    = 'error-reports';
                
        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        $data['setting'] = $this->setting->get_settings($id);

        if( Input::get('_token') )
        {

            $post = $this->post->find( $id );
            $post->post_status  = Input::get('status');
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {

                return Redirect::back()
                               ->with('success', trans('messages.updated', ['variable' => ucfirst($this->single)]));
            } 
        }

        return view($this->view.'.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {
        $this->post->findOrFail($id)->delete();
        return Redirect::route($this->view.'.index', query_vars())
                       ->with('success', trans('messages.trashed', ['variable' => strtolower($this->single)]));
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $post = $this->post->withTrashed()->findOrFail($id);
        $post->restore();
        return Redirect::back()
                       ->with('success', trans('messages.restored', ['variable' => strtolower($this->single)]));
    }

    //--------------------------------------------------------------------------

    public function destroy($id)
    {   
        $this->postmeta->where('post_id', $id)->delete(); 
        $post = $this->post->withTrashed()->find($id);
        $post->forceDelete();
        return Redirect::back()
                       ->with('success', trans('messages.destroyed', ['variable' => strtolower($this->single)]));
    }

    //--------------------------------------------------------------------------

}
