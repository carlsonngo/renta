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

class CategoryController extends Controller
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

        $this->post_type = Input::get('post_type') ? Input::get('post_type') : 'category';
        $this->view      = 'backend.categories';
        $this->single    = trans('backend.category');
        $this->label     = trans('backend.categories');

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
        $data['lang']      = $this->setting->get_setting('site_language');

        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->search($search)
                             ->where('post_type', $this->post_type)
                             ->orderBy(Input::get('sort', 'id'), Input::get('order', 'DESC'))
                             ->paginate(Input::get('rows', 15));

        $data['count'] = $this->post
                              ->search($search)
                              ->where('post_type', $this->post_type)
                              ->count();

        $data['all'] = $this->post->site()->where('post_type', $this->post_type)->count();

        $data['trashed'] = $this->post->withTrashed()
                                      ->site()
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
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['lang']   = $this->setting->get_setting('site_language');

        if( Input::get('_token') )
        {
            $title = ($data['lang'] == 'en') ? 'name' : $data['lang'].'_name';
            $plural_name = ($data['lang'] == 'en') ? 'plural_name' : $data['lang'].'_plural_name';

            $rules = [
                $title        => 'required',
                $plural_name  => 'required',
                'slug'        => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $post = $this->post;

            $post->site_id      = $this->site_id;
            $post->post_author  = $this->user_id;
            $post->post_content = Input::get('description');   
            $post->post_title   = $slug = Input::get('name') ?? Input::get($title);
            $post->post_name    = Input::get('slug') ? text_to_slug(Input::get('slug')) : text_to_slug($slug);
            $post->post_parent  = Input::get('post_parent') ? Input::get('post_parent') : 0;
            $post->post_type    = $this->post_type;
            $post->post_status  = 'actived';

            if( $post->save() ) {

                $inputs = Input::except(['_token', 'name', 'description', 'slug', 'post_parent', 'status', 'post_type', 'lang']);

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));                                         
                }

                return Redirect::back()
                               ->with('success', trans('messages.added', ['variable' => strtolower($this->single)]));
            } 
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

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        if( Input::get('_token') )
        {
            $title = ($data['lang'] == 'en') ? 'name' : $data['lang'].'_name';
            $plural_name = ($data['lang'] == 'en') ? 'plural_name' : $data['lang'].'_plural_name';

            $rules = [
                $title        => 'required',
                $plural_name  => 'required',
                'slug'        => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $post = $this->post->find( $id );

            $post->site_id      = $this->site_id;
            $post->post_author  = $this->user_id;
            $post->post_content = Input::get('description');                
            $post->post_title   = $slug = Input::get('name') ?? Input::get($title);
            $post->post_name    = Input::get('slug') ? text_to_slug(Input::get('slug')) : text_to_slug($slug);
            $post->post_status  = Input::get('status');
            $post->post_parent  = Input::get('post_parent') ? Input::get('post_parent') : 0;
            $post->post_type    = $this->post_type;
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {
                
                $inputs = Input::except(['_token', 'name', 'description', 'slug', 'post_parent', 'status', 'post_type', 'lang']);

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_val));                                         
                }

                return Redirect::back()
                               ->with('success', trans('messages.updated', ['variable' => ucfirst($this->single)]));
            } 
        }

        return view($this->view.'.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {
        $this->post->site()->findOrFail($id)->delete();
        return Redirect::route($this->view.'.index', query_vars())
                       ->with('success', trans('messages.trashed', ['variable' => strtolower($this->single)]));
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $post = $this->post->site()->withTrashed()->findOrFail($id);
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
