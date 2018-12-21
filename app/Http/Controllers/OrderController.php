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

class OrderController extends Controller
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

	    $this->view      = 'backend.shop.orders';
        $this->post_type = 'order';
        $this->single    = trans('backend.order');
        $this->label     = trans('backend.orders');  

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
        $data['module']    = 'orders';
    
        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->search($search)
                             ->where('post_type', $this->post_type)
                             ->orderBy(Input::get('sort', 'id'), Input::get('order', 'DESC'))
                             ->paginate(10);

        $data['count'] = $this->post
                              ->search($search)
                              ->where('post_title', 'LIKE', '%'.Input::get('s').'%')
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
        $data['module']    = 'orders';
                
        $data['lang'] = $this->setting->get_setting('site_language');

        if( Input::get('_token') )
        {
            $rules = [
                'title'  => 'required',
                'status' => 'required',
            ];    

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
            $post->post_title   = $title = Input::get('title');
            $post->post_name    = Input::get('slug') ? text_to_slug(Input::get('slug')) : text_to_slug($title);
            $post->post_type    = $this->post_type;
            $post->post_status  = Input::get('status');

            if( $post->save() ) {

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
        $data['module']    = 'orders';

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        $data['notes'] = $this->post
                             ->where('post_type', 'order-note')
                             ->where('post_parent', $id)
                             ->get();

        if( Input::get('_token') )
        {
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

            $inputs = Input::except(['_token', 'post_type', 'status', 'lang']);
        
            $post = $this->post->find( $id );

            $post->site_id      = $this->site_id;
            
            $post->post_status  = Input::get('status');
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {
                                                 
                $inputs['date_ordered'] = date_formatted_b(Input::get('date_ordered'));                                        
                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_val));
                }
                
                return Redirect::back()
                               ->with('success', trans('messages.updated', ['variable' => ucfirst($this->single)]));
            } 
        }
        
        if( Input::get('gotopay-check-order') ) {

            $date = date('Y-m-d H:i:s');
            $gpay = new \App\GotoPay();
            $data = $gpay->check_order($info, $info->billing_zipcode);  

            if( $data ) {
                $pay['order_status'] = $data->status;
                $pay['order_status_updated'] = $date;            

                foreach ($pay as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_val));
                }

                return Redirect::back()
                               ->with('success', 'Order has been updated and checked on '.$date);
            }

            return Redirect::back()->with('info', 'Status not change!');
        }

        return view($this->view.'.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id ='')
    {
        $this->post->findOrFail($id)->delete();
        return Redirect::route($this->view.'.index', query_vars())
                       ->with('success', trans('messages.trashed', ['variable' => strtolower($this->single)]));
    }

    //--------------------------------------------------------------------------

    public function restore($id ='')
    {   
        $post = $this->post->withTrashed()->findOrFail($id);
        $post->restore();
        return Redirect::back()
                       ->with('success', trans('messages.restored', ['variable' => strtolower($this->single)]));
    }

    //--------------------------------------------------------------------------

    public function destroy($id ='')
    {   
        $this->postmeta->where('post_id', $id)->delete(); 
        $post = $this->post->withTrashed()->find($id);
        $post->forceDelete();
        return Redirect::back()
                       ->with('success', trans('messages.destroyed', ['variable' => strtolower($this->single)]));
    }

    //--------------------------------------------------------------------------

}
