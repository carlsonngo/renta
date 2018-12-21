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

class DomainController extends Controller
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

	    $this->view      = 'backend.domains';
        $this->post_type = 'domain';
        $this->single    = trans('backend.domain');
        $this->label     = trans('backend.domains');  

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
        $data['module']    = 'domains';
    
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

    public function add()
    {
        $data['single']    = $this->single;                                      
        $data['label']     = $this->label; 
        $data['view']      = $this->view;
        $data['post']      = $this->post;
        $data['post_type'] = $this->post_type;

        $data['lang'] = $this->setting->get_setting('site_language');

        if( Input::get('_token') )
        {
            $rules = [
                'name'   => 'required|unique:posts,post_title',
                'url'    => 'required',
                'status' => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }
            $inputs = Input::except(['_token', 'name', 'description', 'url', 'status', 'lang']);

            $post = $this->post;
            $post->site_id      = 0;
            $post->post_author  = $this->user_id;
            $post->post_content = Input::get('description');                
            $post->post_title   = $title = Input::get('name');
            $post->post_name    = Input::get('url');
            $post->post_type    = $this->post_type;
            $post->post_status  = Input::get('status');

            if( $post->save() ) {

                $domain_info = [
                    'id'   => $post->id,
                    'name' => Input::get('name'),
                    'url'  => Input::get('url'),
                ];
                regsiter_domain( $domain_info );

                unset($inputs['bank_account'][0]);

                $inputs['site_language'] = $inputs['localization'] ? $inputs['site_language'] : 'en';
                
                $this->setting->update_metas($inputs, $post->id);
                
                return Redirect::route($this->view.'.edit', [$post->id, query_vars()])
                               ->with('success', trans('messages.added', ['variable' => strtolower($this->single)]));
            } 
        }

        return view($this->view.'.add', $data);
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
        
        $data['info'] = $info = $this->post->find( $id );

        foreach ((array)$this->setting->get_settings($id) as $s_k => $s_v) {
            $data['info'][$s_k] = $s_v;
        }

        $data['setting'] = $this->setting->get_settings($id);

        if( Input::get('_token') )
        {
            $rules = [
                'name'   => 'required|unique:posts,post_title,'.$id.',id',
                'url'    => 'required',
                'status' => 'required',
            ];     

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::except(['_token', 'name', 'description', 'url', 'status', 'lang']);

            $post = $this->post->find( $id );

            $post->post_content = Input::get('description');                
            $post->post_title   = $title = str_replace(' ', '', Input::get('name'));
            $post->post_name    = Input::get('url');
            $post->post_type    = $this->post_type;
            $post->post_status  = Input::get('status');
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {

                $domain_info = [
                    'id'   => $id,
                    'name' => $title,
                    'url'  => Input::get('url'),
                ];
                regsiter_domain( $domain_info );                                   
        
                unset($inputs['bank_account'][0]);

                $inputs['site_language'] = $inputs['localization'] ? $inputs['site_language'] : 'en';

                $this->setting->update_metas($inputs, $id);
                
                return Redirect::back()
                               ->with('success', trans('messages.updated', ['variable' => ucfirst($this->single)]));
            } 
        }

        return view($this->view.'.edit', $data);
    }

    public static function auto_register() {

        $post = new Post();

        $post->site_id      = 1;
        $post->post_author  = 1;
        $post->post_content = 'Automated domain registration';                
        $post->post_title   = $name = request()->server->get('SERVER_NAME');
        $post->post_name    = $url  = request()->server->get('REQUEST_SCHEME').'://'.request()->server->get('HTTP_HOST');
        $post->post_type    = 'domain';
        $post->post_status  = 'actived';

        if( $post->save() ) {

            $inputs = array(
              "site_title" => $name,
              "footer_title" => $name,
              "admin_email" => null,
              "site_language" => "en",
              "current_theme" => "default",
              "site_logo" => null,
              "google_translate" => 0,
              "footer_logo" => null,
              "css_theme" => "elegant",
              "mail_encryption" => "tls",
              "mail_host" => "smtp.gmail.com",
              "mail_port" => "587",
              "mail_username" => null,
              "mail_password" => null,
              "meta_title" => null,
              "meta_keywords" => null,
              "meta_description" => null,
              "facebook_title" => null,
              "facebook_description" => null,
              "twitter_title" => null,
              "twitter_description" => null,
              "currency" => "USD",
              "events_module" => 0,
              "bookings_module" => 0,
              "shop_module" => 0,
              "payment_methods" => '["gotopay","paypal"]',
              "payment_method" => "paypal",
              "front_page" => "default",
              "debug_mode" => 0,
              "maintenance_mode" => 0,
              "maintenance_bg" => null,
              "paypal" => null,
              "localization" => 0,
              "gallery_module" => 0,
              "error_reports" => 0,
              "enable_coupon" => 0,
            );

            foreach (site_modules() as $module) {
                $inputs[$module['name']] = 0;
            }

            $domain_info = [
                'id'   => $post->id,
                'name' => $name,
                'url'  => $url,
            ];
            regsiter_domain( $domain_info );

            $setting = new Setting();
            $setting->update_metas($inputs, $post->id);

        
            return Redirect::back()
                           ->with('success', 'New domain has been successfully registered.');
        }

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
