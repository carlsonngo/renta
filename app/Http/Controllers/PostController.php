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

class PostController extends Controller
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

        $post_type = Input::get('post_type');
	    
	    $this->view = 'backend.posts';

        if( $post_type ) {
	        $this->post_type = $post_type;
	        $this->single    = trans('backend.'.$post_type);
	        $this->label     = trans('backend.'.str_plural($post_type));        	
        }

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
        $data['module']    = str_plural($this->post_type);

        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->search($search)
                             ->where('post_type', $this->post_type)
                             ->orderBy(Input::get('sort', 'id'), Input::get('order', 'DESC'))
                             ->paginate(10);

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
        $data['single']    = $this->single;                                      
        $data['label']     = $this->label; 
        $data['view']      = $this->view;
        $data['post']      = $this->post;
        $data['post_type'] = $this->post_type;

        $data['lang'] = $this->setting->get_setting('site_language');

        if( Input::get('_token') ) 
        {
            $title = ($data['lang'] == 'en') ? 'title' : $data['lang'].'_title';

            $rules = [
                $title   => 'required',
                'slug'   => 'required|unique_post:'.$this->site_id,
                'status' => 'required',
            ];     

            Validator::extend('unique_post', function ($attribute, $value, $param, $validator) {
                $post = $this->post->where('post_name', $value)
                                   ->where('site_id', $param[0])
                                   ->exists();
                                   
                return $post ? false : true;
            });  
          
            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }
            $inputs = Input::except(['_token', 'title', 'content', 'slug', 'status', 'lang']);

            $post = $this->post;
            $post->site_id      = $this->site_id;
            $post->post_author  = $this->user_id;
            $post->post_content = Input::get('content');                
            $post->post_title   = $slug = Input::get('title') ?? Input::get($title);
            $post->post_name    = Input::get('slug') ? text_to_slug(Input::get('slug')) : text_to_slug($slug);
            $post->post_type    = $this->post_type;
            $post->post_status  = Input::get('status');

            if( $post->save() ) {

	           	if( Input::get('date_start') ) {
		            $inputs['date_start'] = date_formatted_b($inputs['date_start']);
	           	}

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
                }

                return Redirect::route($this->view.'.edit', [$post->id, query_vars()])
                               ->with('success', trans('messages.added', ['variable' => strtolower($this->single)]));
            } 
        }

        $c=1;

        $data['categories'] = [];
        foreach ($this->post->site()->where(['post_type' => $this->post_type.'-category'])->get() as $category) {
            $data['categories'][$c++] = array(
                'id'        => $category->id, 
                'parent_id' => $category->post_parent, 
                'name'      => $category->post_title
            );
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
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        if( Input::get('_token') )
        {
            $title = ($data['lang'] == 'en') ? 'title' : $data['lang'].'_title';

            $rules = [
                $title   => 'required',
                'slug'   => 'required|unique_post:'.$id.','.$this->site_id,
                'status' => 'required',
            ];     

            Validator::extend('unique_post', function ($attribute, $value, $param, $validator) {
                $post = $this->post->where('post_name', $value)
                                   ->where('id', '!=', $param[0])
                                   ->where('site_id', $param[1])
                                   ->exists();

                return $post ? false : true;
            });

            if(  $info->post_type == 'event' ) {
                $rules = $rules + [
                    'date_start'  => 'required',
                    'time_start'  => 'required',
                ];                
            }

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::except(['_token', 'title', 'content', 'slug', 'status', 'lang']);

            $post = $this->post->find( $id );

            $post->site_id      = $this->site_id;
            $post->post_content = Input::get('content');                
            $post->post_title   = $slug = Input::get('title') ?? Input::get($title);
            $post->post_name    = Input::get('slug') ? text_to_slug(Input::get('slug')) : text_to_slug($slug);
            $post->post_type    = $this->post_type;
            $post->post_status  = Input::get('status');
            $post->updated_at   = date('Y-m-d H:i:s');
           	
           	if( Input::get('date_start') ) {
	            $inputs['date_start']  = date_formatted_b($inputs['date_start']);
                $inputs['event_start'] = $inputs['date_start'].' '.$inputs['time_start'].':00';
           	}

            if( $post->save() ) {
                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_val));                                         
                }

                return Redirect::back()
                               ->with('success', trans('messages.updated', ['variable' => ucfirst($this->single)]));
            } 
        }

        $c=1;                                              

        $data['categories'] = [];
        foreach ($this->post->site()->where(['post_type' => $this->post_type.'-category'])->get() as $category) {
            $data['categories'][$c++] = array(
                'id'        => $category->id, 
                'parent_id' => $category->post_parent, 
                'name'      => $category->post_title
            );
        }

        $data['blog'] = ($info->post_type == 'post') ? strtolower($info->categoryList) : '/';

        return view($this->view.'.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {
        $this->post->site()->findOrFail($id)->delete();
         return Redirect::back()
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

    public function post($category='', $slug='')
    {   
       $data['category'] = $category;

    	if( $slug ) {
    		$post_status[] = 'published';

    		if( $this->user_id ) {
	    		$post_status[] = 'draft';
    		}
    	
	        $data['info'] = $info = $this->post->site()
                                               ->where( 'post_name', $slug )
	                                           ->whereIn('post_status', $post_status)
	                                           ->whereIn('post_type', ['post', 'page', 'event'])
	                                           ->firstOrFail();
	 
	        foreach ($info->postmetas as $postmeta) {
	            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
	        }

            if( $info->post_type == 'event' ) {
                return Redirect::route('frontend.events.single', $slug);
            }

	        $template = $info->template ? $info->template : 'default';

	        if( ! file_exists( '../resources/views/frontend/templates/'.$template.'.blade.php') ) {
	        	return view('frontend.templates.default', $data);    
	        }

	        return view('frontend.templates.'.$template, $data);  

    	} else {

            if( $category == 'news' ) {

                $rows = $this->post->site()
                                   ->where('post_type', 'post')
                                   ->orderBy('id', 'DESC');
        
                $data['rows'] = $rows->paginate(10);

                return view('frontend.news', $data);  

            }

	        $data['cat'] = $cat = $this->post->site()
                                             ->where('post_name', $category)
								             ->where('post_type', 'LIKE', '%category%')
								             ->first();

            if( ! $cat ) {
            	return $this->post('', $category);
        	}

	        $rows = $this->post
	            ->select('posts.*', 'm1.meta_value as category')
	            ->from('posts')
	            ->join('postmeta AS m1', function ($join) use ($cat) {
                $join->on('posts.id', '=', 'm1.post_id')
                    ->where('m1.meta_key', '=', 'category')
                    ->where('m1.meta_value', 'LIKE', '%'.@$cat->id.'%');
                })->site()
                  ->orderBy('id', 'DESC');
    
	        $data['rows'] = $rows->paginate(10);

	        return view('frontend.archive', $data);  

    	}

    }

    //--------------------------------------------------------------------------
       
    public function clone($id)
    {
       if($id) {
            $post    = $this->post->find($id);

            $newPost = $post->replicate();
            $newPost->site_id     = $this->site_id;
            $newPost->post_author = $this->user_id;
            $newPost->post_title  = $post->post_title.' -copy';
            $newPost->post_name   = $post->post_name.strtolower('-'.str_random(6));
            $newPost->post_status = 'draft';

            $newPost->save();

            foreach ($this->postmeta->where('post_id', $id)->get() as $metaDetail) {
                $newMetaDetail = new PostMeta();
                $newMetaDetail->site_id    = $this->site_id;
                $newMetaDetail->post_id    = $newPost->id;
                $newMetaDetail->meta_key   = $metaDetail->meta_key;
                $newMetaDetail->meta_value = check_postmeta($post->post_type, $metaDetail->meta_key, $metaDetail->meta_value);
                $newMetaDetail->save();
            }

            return Redirect::back()
                           ->with('success', 'Clone has been cloned');
        }
    }

    //--------------------------------------------------------------------------     
}
