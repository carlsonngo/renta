<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config, DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;

class UserController extends Controller
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

        $this->view      = 'backend.users';
        $this->single    = trans('backend.user');
        $this->label     = trans('backend.users');

        $this->middleware(function ($request, $next) {
            $this->site_id = get_domain();
            $this->user_id = Auth::check() ? Auth::user()->id : 0;
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function index()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;
        $data['module'] = 'users';

        parse_str( query_vars(), $search );

        $data['rows'] = $this->user
                             ->search($search)
                             ->paginate(Input::get('rows', 15));

        $data['count'] = $this->user
                              ->search($search)
                              ->count();

        $data['all'] = $this->user->site()->where('users.id', '!=', 1)->count();

        $data['trashed'] = $this->user->withTrashed()
                                      ->site()
                                      ->where('deleted_at', '<>', '0000-00-00')
                                      ->count();

        /* Perform bulk actions */             
        if( Input::get('ids') ) {                         
	        if( Input::get('action') == 'trash' ) {
	            foreach( Input::get('ids') as $id ) {
	                User::find($id)->delete();
	            }
	            return Redirect::back()
	                           ->with('success', trans('messages.trashed', ['variable' => strtolower($this->single)]));
	        }

	        if( Input::get('action') == 'restore') {
	            foreach( Input::get('ids') as $id ) {
	                $user = User::withTrashed()->findOrFail($id);
	                $user->restore();
	            }
	            return Redirect::back()
	                           ->with('success', trans('messages.restored', ['variable' => strtolower($this->single)]));
	        }

	        if( Input::get('action') == 'destroy') {
	            foreach( Input::get('ids') as $id ) {
	                $this->user->force_destroy($id);
	            }
	            return Redirect::back()
	                           ->with('success', trans('messages.destroyed', ['variable' => strtolower($this->single)]));
	        }
    	}
    	
        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function account()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;

        $data['info'] = $info = $this->user->find( $this->user_id );
        foreach ($info->usermetas as $usermeta) {
            $data['info'][$usermeta->meta_key] = $usermeta->meta_value;
        }

        $user = $this->user->find( $this->user_id );
        
        $profile_picture = $data['info']->profile_picture;

        if( Input::get('_token') ) {
            
            $rules = [
                'email'      => 'required|email|max:64|unique_post:email,'.$this->user_id.','.$this->site_id,
                'username'   => 'required|max:64|unique_post:username,'.$this->user_id.','.$this->site_id,
                'firstname'  => 'required|min:1|max:32',
                'lastname'   => 'required|min:1|max:32',
            ];      

            Validator::extend('unique_post', function ($attribute, $value, $param, $validator) {
                $user = $this->user->where($param[0], $value)
                                   ->where('id', '!=', $param[1])
                                   ->where('site_id', $param[2])
                                   ->exists();

                return $user ? false : true;
            });

            if( $new_password = Input::get('new_password') ) {
                $rules['new_password']              = 'required|min:6|max:64|confirmed';
                $rules['new_password_confirmation'] = 'required|min:6';
            }

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.account')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user->fill( Input::all() );   

            if( $new_password ) {
                $user->password = Hash::make( $new_password );
            }

            $user->usermeta   = json_encode( Input::except(['_token', 'password', 'status', 'group', 'lang']) );             
            $user->updated_at = date('Y-m-d H:i:s');

            if( Input::hasFile('file') ) {
                $pic = upload_image(Input::file('file'), 'uploads/'.$this->site_id.'/users/'.$this->user_id, $profile_picture, 'compress');
                $this->usermeta->update_meta($this->user_id, 'profile_picture', $pic);       
            }

            if( $user->save() ) {
                return Redirect::back()
                               ->with('success', trans('messages.profile_updated'));
            } 

        }

        return view($this->view.'.account', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;
                   
        if( Input::get('_token') )
        {

            $rules = [
                'email'      => 'required|email|max:64|unique_post:email,'.$this->site_id,
                'username'   => 'required|max:64|unique_post:username,'.$this->site_id,
                'firstname'  => 'required|min:1|max:32',
                'lastname'   => 'required|min:1|max:32',
                'password'   => 'required|min:6|max:32',
                'group'      => 'required',
            ];      

            Validator::extend('unique_post', function ($attribute, $value, $param, $validator) {
                $user = $this->user->where($param[0], $value)
                                   ->where('site_id', $param[1])
                                   ->exists();

                return $user ? false : true;
            });

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.add', query_vars())
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs =  Input::except(['_token', 'firstname', 'lastname', 'email', 'username', 'password', 'group', 'lang']);

            $user = $this->user;

            $user->fill( Input::all() );   
            $user->site_id  = $this->site_id;
            $user->password = Hash::make( Input::get('password') );  
            $user->usermeta = json_encode( Input::all() );   
            $user->status   = 'actived';  
         
            if( $user->save() ) {

                $id = $user->id;

                if( Input::hasFile('file') ) {
                    $inputs['profile_picture'] = upload_image(Input::file('file'), 'uploads/'.$this->site_id.'/users/'.$id, '', 'compress');    
                }          

				if( Input::get('group') == 'customer' ) {
					$inputs['membership']  = 'standard';
				}

               foreach ($inputs as $meta_key => $meta_val) {
                    $this->usermeta->update_meta($id, $meta_key, array_to_json($meta_val));                                         
                }


                // BEGIN EMAIL CONFIRMATION 
                $data['email'] = $this->post->where('post_type', 'email')
                                            ->where('post_name', 'new-user')
                                            ->first();

                $patterns = [
                    '/\[firstname\]/'         => ucwords(Input::get('firstname')),
                    '/\[lastname\]/'          => ucwords(Input::get('lastname')),
                    '/\[username\]/'          => Input::get('username'),
                    '/\[password\]/'          => Input::get('password'),
                    '/\[site_title\]/'        => $this->setting->get_setting('site_title'),
                    '/\[group\]/' 			  => user_group(Input::get('group')),
                    '/\[email_address\]/'     => Input::get('email'),
                    '/\[date_register\]/'     => date_formatted(date('Y-m-d')),
                    '/\[login_url\]/'         => route('auth.login'),
                ];

                $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
                $data['site_title']  = $this->setting->get_setting('site_title');
                $data['admin_email'] = $this->setting->get_setting('admin_email');
                $data['subject']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_title);
                $data['user_email']  = Input::get('email');

                Mail::send('emails.default', $data, function($message) use ($data) {
                    $message->from($data['admin_email'], $data['site_title'])
                            ->to($data['user_email'])
                            ->subject( $data['subject'] );
                });
                // END EMAIL CONFIRMATION

                return Redirect::route($this->view.'.edit', [$user->id, query_vars()])
                               ->with('success', trans('messages.added', ['variable' => strtolower($this->single)]));
            } 
        }

        return view($this->view.'.add', $data);
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;


        $data['info'] = $info = $this->user->find( $id);
        foreach ($info->usermetas as $usermeta) {
            $data['info'][$usermeta->meta_key] = $usermeta->meta_value;
        }

        if( Input::get('_token') )
        {
            $rules = [
                'email'      => 'required|email|max:64|unique_post:email,'.$id.','.$this->site_id,
                'username'   => 'required|max:64|unique_post:username,'.$id.','.$this->site_id,
                'firstname'  => 'required|min:1|max:32',
                'lastname'   => 'required|min:1|max:32',
                'group'      => 'required',
            ];         	  

            Validator::extend('unique_post', function ($attribute, $value, $param, $validator) {
                $user = $this->user->where($param[0], $value)
                                   ->where('id', '!=', $param[1])
                                   ->where('site_id', $param[2])
                                   ->exists();

                return $user ? false : true;
            });

            if( $password = Input::get('password') ) {
                $rules['password'] = 'required|min:6|max:32';
            }

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.edit', [$id, query_vars()])
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user = $this->user->find( $id );

            $user->fill( Input::all() );   

            if( $info->group == 'customer' && @$info->membership == 'premium' && Input::get('confirmed') == 1 ) {            
                $password  = strtoupper(str_random(5));
            }
            
            if( $password ) {
                $user->password = Hash::make( $password );
            }

            $inputs = Input::except(['_token', 'firstname', 'lastname', 'email', 'group', 'username', 'password', 'status', 'lang']);

            $user->site_id    = $this->site_id;
            $user->usermeta   = json_encode( Input::except(['_token', 'password', 'status', 'group', 'lang']) ); 
            $user->updated_at = date('Y-m-d H:i:s');

            if( Input::hasFile('file') ) {
                $pic = upload_image(Input::file('file'), 'uploads/'.$this->site_id.'/users/'.$id, $info->profile_picture, 'compress');
                $inputs['profile_picture'] =  $pic;
            }    

            if( $user->save() ) {

                if( $info->group == 'customer' && @$info->membership == 'premium' && Input::get('confirmed') == 1 ) {

                    // BEGIN EMAIL CONFIRMATION 
                    $data['email'] = $this->post->where('post_type', 'email')
                                                ->where('post_name', 'confirm-registration')
                                                ->first();

                    $patterns = [
                        '/\[firstname\]/'         => ucwords($user->firstname),
                        '/\[lastname\]/'          => ucwords($user->lastname),
                        '/\[password\]/'          => $password,
                        '/\[site_title\]/'        => $this->setting->get_setting('site_title'),
                        '/\[membership_type\]/'   => $info->membership,
                        '/\[email_address\]/'     => $user->email,
                        '/\[date_register\]/'     => date_formatted(date('Y-m-d')),
                        '/\[login_url\]/'         => route('auth.login'),
                    ];

                    $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
                    $data['site_title']  = $this->setting->get_setting('site_title');
                    $data['admin_email'] = $this->setting->get_setting('admin_email');
                    $data['subject']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_title);
                    $data['user_email']  = $user->email;

                    Mail::send('emails.default', $data, function($message) use ($data) {
                        $message->from($data['admin_email'], $data['site_title'])
                                ->to($data['user_email'])
                                ->subject( $data['subject'] );
                    });
                    // END EMAIL CONFIRMATION

	                $this->user->find($id)->update(['status' => 'actived']);

                }

               foreach ($inputs as $meta_key => $meta_val) {
                    $this->usermeta->update_meta($id, $meta_key, array_to_json($meta_val));                                         
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
        $this->user->site()->findOrFail($id)->delete();
        return Redirect::back()
                       ->with('success', trans('messages.trashed', ['variable' => strtolower($this->single)]));
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $user = $this->user->site()->withTrashed()->findOrFail($id);
        $user->restore();
        return Redirect::back()
                       ->with('success', trans('messages.restored', ['variable' => strtolower($this->single)]));
    }

    //--------------------------------------------------------------------------
  
    public function destroy($id)
    {   
        $this->user->force_destroy($id);

        return Redirect::back()
                       ->with('success', trans('messages.destroyed', ['variable' => strtolower($this->single)]));
    }

    //--------------------------------------------------------------------------
    
    public function login($id)
    {
        Auth::loginUsingId($id);        

        Session::put('user_id', $id);

        return Redirect::route('backend.general.dashboard');

    }

    //--------------------------------------------------------------------------

}
