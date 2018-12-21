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

class GeneralController extends Controller
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

        $this->view  = 'backend';
        $this->label = trans('backend.dashboard');  

        $this->middleware(function ($request, $next) {
            $this->site_id = get_domain();
            $this->user_id = Auth::check() ? Auth::user()->id : 0;
            return $next($request);
        });

    }

    //--------------------------------------------------------------------------

    public function dashboard()
    {                                    
        $data['label'] = $this->label; 
        $data['view']  = $this->view;

        $data['users']  = $this->user->site()->where('users.id', '!=', 1)->count(); 

        $data['pages']  = $this->post->site()->where('post_type', 'page')->count();
        $data['posts']  = $this->post->site()->where('post_type', 'post')->count();
        $data['events'] = $this->post->site()->where('post_type', 'event')->count();

        $data['groups'] = $this->post->site()->where('post_type', 'group')->count();
        $data['products'] = $this->post->site()->where('post_type', 'product')->count();
        $data['coupons'] = $this->post->site()->where('post_type', 'coupon')->count();
        $data['galleries']  = $this->post->site()->where('post_type', 'gallery')->count();
        $data['bookings']  = $this->post->site()
                                        ->whereIn('post_type', ['reservation', 'ticket'])
                                        ->whereIn('post_status', ['pending', 'confirmed'])
                                        ->count();

        $data['reservations']  = $this->post->site()
                                        ->where('post_type', 'reservation')
                                        ->whereIn('post_status', ['pending', 'confirmed'])
                                        ->count();

        $data['orders']  = $this->post->site()
                                        ->where('post_type', 'order')
                                        ->whereIn('post_status', ['pending', 'on-hold'])
                                        ->count();

        $data['tickets']  = $this->post->site()
                                        ->where('post_type', 'ticket')
                                        ->whereIn('post_status', ['pending', 'confirmed'])
                                        ->count();

        return view($this->view.'.dashboard', $data);
    }

    //--------------------------------------------------------------------------
   
    public function note($id ='', $delete = false)
    {   
        if( $delete == true ) {
            $this->post->findOrFail($id)->delete();
            return;
        }

        $name = ucwords(Auth::user()->firstname.' '.Auth::user()->lastname);

        $post = $this->post;

        $post->site_id      = $this->site_id;
        $post->post_author  = $this->user_id;       
        $post->post_title   = 'added on <b>'.date('F d, Y').'</b> at <b>'.date('H:i').'</b> by <b>'.$name.'</b>';                
        $post->post_content = Input::get('note');                
        $post->post_parent  = $id;
        $post->post_status  = 'actived';
        $post->post_type    = 'order-note';
        $post->created_at   = date('Y-m-d H:i:s');

        $post->save();

        $data['note'] = $post; 

        return view('backend.partials.note', $data);
    }

    //--------------------------------------------------------------------------

}
