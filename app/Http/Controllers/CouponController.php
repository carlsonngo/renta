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
use App\Permission;

class CouponController extends Controller
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
    protected $permission;


    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Request $request, Permission $permission)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->setting  = $setting;
        $this->request  = $request;
        $this->permission = $permission;

        $this->view      = 'backend.shop.coupons';
        $this->post_type = 'coupon';
        $this->single    = trans('backend.coupon');
        $this->label     = trans('backend.coupons');  

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
        $data['module']    = 'coupons'; 

        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->search($search)
                             ->where('post_type', $this->post_type)
                             ->orderBy('id', 'DESC')
                             ->paginate(15);

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
                               ->with('success', trans('messages.trashed', ['variable' => $data['single']]));
            }

            if( Input::get('action') == 'restore') {
                foreach( Input::get('ids') as $id ) {
                    $user = Post::withTrashed()->findOrFail($id);
                    $user->restore();
                }
                return Redirect::back()
                               ->with('success', trans('messages.restored', ['variable' => $data['single']]));
            }

            if( Input::get('action') == 'destroy') {
                foreach( Input::get('ids') as $id ) {
                    PostMeta::where('post_id', $id)->delete(); 
                    $post = Post::withTrashed()->find($id);
                    $post->forceDelete();
                }
                return Redirect::back()
                               ->with('success', trans('messages.destroyed', ['variable' => $data['single']]));
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
        $data['setting']   = $this->setting;

        if( Input::get('_token') )
        {
            $rules = [
                'name' => 'required',
                'discount_type' => 'required',
                'amount' => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }
            
            $inputs = Input::except(['_token', 'name', 'status', 'description', 'discount_type', 'lang']);

            $post = $this->post;

            $post->site_id      = $this->site_id;
            $post->post_author  = $this->user_id;
            $post->post_title   = Input::get('name');
            $post->post_content = Input::get('description');
            $post->post_name    = Input::get('discount_type');
            $post->post_type    = $this->post_type;
            $post->post_status  = Input::get('status');

            if( $post->save() ) {

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));                                         
                }

                return Redirect::route($this->view.'.edit', $post->id)
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
        $data['setting']   = $this->setting;

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        if( Input::get('_token') )
        {
            $rules = [
                'name' => 'required',
                'discount_type' => 'required',
                'amount' => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {

                return Redirect::route($this->view.'.edit', $id)
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::except(['_token', 'name', 'status', 'description', 'discount_type', 'lang']);
            
            $post = $this->post->find( $id );
            
            $post->site_id      = $this->site_id;
            $post->post_title   = Input::get('name');
            $post->post_content = Input::get('description');
            $post->post_name    = Input::get('discount_type');
            $post->post_status  = Input::get('status');
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {

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
        $this->post->findOrFail($id)->delete();
        return Redirect::route($this->view.'.index', query_vars())
                       ->with('success', trans('messages.trashed', ['variable' => strtolower($this->single)]));
    }

    //--------------------------------------------------------------------------

    public function apply()
    {
        $cart = session('cart');

        $cart['coupon_code'] = $coupon_code = Input::get('coupon_code');
        $coupon = $this->post->where('post_title', $coupon_code)
                             ->where('post_type', 'coupon')
                             ->where('post_status', 'actived')
                             ->first();

        if( $coupon ) {

            foreach ($coupon->postmetas as $postmeta) {
                $coupon[$postmeta->meta_key] = $postmeta->meta_value;
            }

            $coupon_amount = $coupon->amount;
            if( $coupon->post_name == 'percent') {
                $coupon_amount = $coupon->amount / 100 * $cart['total'];
            } 

            $date_start = date_formatted_b($coupon->date_start).' '.$coupon->time_start;
            $date_end = date_formatted_b($coupon->date_end).' '.$coupon->time_end;

            if( ! date_validity($date_start, $date_end) ) {       
                $msg = 'Sorry, coupon is already expired!';
                if( $this->request->ajax() ) return json_encode(['error' => true, 'msg' => $msg]);                         
                return Redirect::back()->with('error', $msg);                      
            }

            if( $coupon->one_time_use == 1 ) {
                $order = $this->post->where('post_content', 'LIKE', '%"coupon_code":"'.$coupon_code.'"%')
                                    ->where('post_type', 'order')
                                    ->first();
                if( $order ) {
                    $msg = 'Sorry, coupon has already been used!';
                    if( $this->request->ajax() ) return json_encode(['error' => true, 'msg' => $msg]);                         
                    return Redirect::back()->with('error', $msg);                                     
                }
            }

            if( $coupon_amount > $cart['subtotal'] ) {
                $msg = 'Sorry, coupon is not valid for minimum amount!';
                if( $this->request->ajax() ) return json_encode(['error' => true, 'msg' => $msg]);                         
                return Redirect::back()->with('error', $msg);                  
            }

            $cart['coupon_description'] = $coupon->post_content;
            $cart['discount_fee'] = $coupon_amount;
            $cart['total'] = ($cart['subtotal'] - $coupon_amount) + $cart['delivery_fee'];
            session(['cart' => $cart]);

            $msg = 'Coupon code has been applied!';
            if( $this->request->ajax() ) return json_encode(['error' => false, 'msg' => $msg]);                       
            return Redirect::back()->with('success', $msg);   
        } 

        if( $coupon_code && !$coupon ) {
            $msg = 'Coupon code does not exist.';
            if( $this->request->ajax() ) return json_encode(['error' => true, 'msg' => $msg]);                       
            return Redirect::back()->with('error', $msg);                
        } 

    }

    //--------------------------------------------------------------------------

    public function remove()
    {
        $cart = session('cart');
        $cart['discount_fee'] = 0;
        unset($cart['coupon_code']);

        $cart['total'] = $cart['subtotal'] + $cart['delivery_fee'];

        session(['cart' => $cart]);

        return Redirect::back()
                       ->with('success', 'Coupon has been removed!');
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
