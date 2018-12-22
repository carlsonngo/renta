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
use App\Shop;

class CustomerController extends Controller
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
    protected $shop;
    protected $request;

    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Shop $shop, Request $request)
    {
        $this->user       = $user;
        $this->usermeta   = $usermeta;
        $this->post       = $post;
        $this->postmeta   = $postmeta;
        $this->setting    = $setting;
        $this->shop       = $shop;
        $this->request    = $request;

        $this->view = 'shop.customer';

        $this->middleware(function ($request, $next) {
            $this->site_id = get_domain();
            $this->user_id = Auth::check() ? Auth::user()->id : 0;
            return $next($request);
        });

    }

    //--------------------------------------------------------------------------

    public function index()
    {
        $data['info'] = Auth::user();

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function orders()
    {

        $data['rows'] = $this->post
                             ->site()
                             ->where('post_author',  $this->user_id)
                             ->where('posts.post_type', 'order')
                             ->orderBy('id', 'DESC')
                             ->paginate(10);

        return view($this->view.'.orders', $data);
    }

    //--------------------------------------------------------------------------

    public function order_view($id='')
    {
        $data['info'] = $info = $this->post->site()
                                           ->where('post_author',  $this->user_id)
                                           ->where('id', $id )
                                           ->where('post_type', 'order')
                                           ->firstOrFail();
 
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }
        
        $data['cart'] = json_decode($info->post_content);

        return view($this->view.'.order-view', $data);
    }

    //--------------------------------------------------------------------------

    public function addresses()
    {

        $data['info'] = $info = $this->user->find( $this->user_id );
        foreach ($info->usermetas as $usermeta) {
            $data['info'][$usermeta->meta_key] = $usermeta->meta_value;
        }

        $user = $this->user->find( $this->user_id );
        
        if( Input::get('_token') ) {

            $rules = [
                'billing_firstname'     => 'required',
                'billing_lastname'      => 'required',
                'billing_email_address' => 'required|email',
                'billing_phone'         => 'required',
                'billing_address_1'     => 'required',
                'billing_city'          => 'required',
                'billing_state'         => 'required',
                'billing_zipcode'       => 'required',
                'billing_country'       => 'required',
                'shipping_firstname'     => 'required',
                'shipping_lastname'      => 'required',
                'shipping_email_address' => 'required|email',
                'shipping_phone'         => 'required',
                'shipping_address_1'     => 'required',
                'shipping_city'          => 'required',
                'shipping_state'         => 'required',
                'shipping_zipcode'       => 'required',
                'shipping_country'       => 'required',
            ];
            
            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::except(['_token', 'lang']);

            foreach ($inputs as $meta_key => $meta_val) {
                $this->usermeta->update_meta($this->user_id, $meta_key, array_to_json($meta_val));
            }

            return Redirect::back()
                           ->with('success', trans('messages.profile_updated'));
        }

        return view($this->view.'.addresses', $data);
    }

    //--------------------------------------------------------------------------
   
    public function account()
    {

        $data['info'] = $info = $this->user->find( $this->user_id );

        $user = $this->user->find( $this->user_id );
        
        if( Input::get('_token') ) {
            
            $rules = [
                'email'      => 'required|email|max:64|unique:users,email,'.$this->user_id.',id',
                'firstname'  => 'required|min:1|max:32',
                'lastname'   => 'required|min:1|max:32',
            ];      

            if( $new_password = Input::get('new_password') ) {
                $rules['new_password']              = 'required|min:6|max:64|confirmed';
                $rules['new_password_confirmation'] = 'required|min:6';
            }

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user->fill( Input::all() );   

            if( $new_password ) {
                $user->password = Hash::make( $new_password );
            }

            $user->usermeta   = json_encode( Input::except(['_token', 'password', 'status', 'group', 'lang']) );             
            $user->updated_at = date('Y-m-d H:i:s');

            if( $user->save() ) {
                return Redirect::back()
                               ->with('success', trans('messages.profile_updated'));
            } 
        }

        return view($this->view.'.account', $data);
    }

    //--------------------------------------------------------------------------

}
