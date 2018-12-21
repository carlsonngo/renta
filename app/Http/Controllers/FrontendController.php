<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config, Response, View, DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;

class FrontendController extends Controller
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
    protected $stripe;
    protected $request;

    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Request $request)
    {
        $this->user       = $user;
        $this->usermeta   = $usermeta;
        $this->post       = $post;
        $this->postmeta   = $postmeta;
        $this->setting    = $setting;
        $this->request    = $request;

        $this->view = 'frontend';

        $this->middleware(function ($request, $next) {
            $this->site_id = get_domain();
            $this->user_id = Auth::check() ? Auth::user()->id : 0;
            return $next($request);
        });

    }

    //--------------------------------------------------------------------------

    public function home()
    {
        $front = $this->setting->get_setting('front_page');

        $view = in_array($front, ['default', '']) ? 'default' : $front;

        $data['about'] = $about = $this->post->site()
                                             ->where('post_name', 'about-us')
                                             ->first();
        if( $about ) {
            foreach ($about->postmetas as $postmeta) {
                $data['about'][$postmeta->meta_key] = $postmeta->meta_value;
            }
        }                                             

        $data['slider'] = $this->post->site()
                                     ->where('post_name', 'home-slider')->first();

        $data['news'] = $this->post->site()
                                   ->where('post_type', 'post')
                                   ->where('post_status', 'published')
                                   ->orderBy('id', 'DESC')
                                   ->paginate(6);

        return view($this->view.'.templates.front-page.'.$view, $data);
    }

    //--------------------------------------------------------------------------

    public function contact()
    {

    	if( Input::get('_token') ) {
	        $rules = [
	            'name'    => 'required',
	            'email'   => 'required|email',
	            'subject' => 'required',
	            'message' => 'required'
	        ];

	        $validator = Validator::make(Input::all(), $rules);

	        if( ! $validator->passes() ) {
	            return Redirect::back()
	                           ->withErrors($validator)
	                           ->withInput(); 
	        }

	  
	        $data['site_title']  = $this->setting->get_setting('site_title');
	        $data['admin_email'] = $this->setting->get_setting('admin_email');
	        $data['content'] = Input::get('message');

	        Mail::send('emails.default', $data, function($message) use ($data)
	        {
	            $message->from( Input::get('email'), Input::get('firstname') )
	                    ->to( $data['admin_email'], $data['site_title'] )
	                    ->subject( Input::get('subject') );
	        });

	        return Redirect::back()
	                       ->with('success','Your inquiry has been sent!');
    	}

        return view($this->view.'.contact-us');
    }

    //--------------------------------------------------------------------------

    public function galleries()
    {
        $data['galleries'] = $this->post->site()
                                  ->where('post_type', 'gallery')
                                  ->where('post_status', 'published')
                                  ->get();

        return view($this->view.'.galleries', $data);
    }

    //--------------------------------------------------------------------------
   
    public function gallery($id ='')
    {

        $data['row'] = $this->post->site()
                                ->where('id', $id)
                                ->where('post_type', 'gallery')
                                ->where('post_status', 'published')
                                ->first();

        return view($this->view.'.gallery', $data);
    }

    //--------------------------------------------------------------------------

}
