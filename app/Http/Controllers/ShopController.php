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
use App\GotoPay;

class ShopController extends Controller
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
    protected $gotopay;

    public function __construct(GotoPay $gotopay, User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Shop $shop, Request $request)
    {
        $this->user       = $user;
        $this->usermeta   = $usermeta;
        $this->post       = $post;
        $this->postmeta   = $postmeta;
        $this->setting    = $setting;
        $this->shop       = $shop;
        $this->request    = $request;
        $this->gotopay    = $gotopay;

        $this->view = 'shop';
        $this->view_b = 'backend.shop.settings';

        $this->middleware(function ($request, $next) {
            $this->site_id = get_domain();
            $this->user_id = Auth::check() ? Auth::user()->id : 0;
            return $next($request);
        });

    }

    //--------------------------------------------------------------------------

    public function index()
    {

        parse_str( query_vars(), $search );

        $queries = array('price', 'category');

        $key = 'id'; $val = 'DESC';
        if( Input::get('sort') ) {
            list($key, $val) = explode('-', Input::get('sort'));
        }

        $data['rows'] = $this->post->site()
                                   ->search($search, ['price'], $queries)
                                   ->where('post_type', 'product')
                                   ->where('post_status', 'actived')
                                   ->orderBy($key, $val)
                                   ->paginate(16);


        $data['categories'] = $this->post->site()
                                         ->where('post_type', 'product-category')
                                         ->where('post_status', 'actived')
                                         ->get()
                                         ->pluck('post_title', 'id')
                                         ->toArray();

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function review()
    {

        $post = $this->post->site()
                           ->where('post_type', 'comment')
                           ->where('post_parent', Input::get('pid'))
                           ->where('post_author', $this->user_id)
                           ->first();    
        
        if( Input::get('comment') ) {
            $post = $post ? $post : new Post();

            $post->site_id      = $this->site_id;
            $post->post_author  = $this->user_id;
            $post->post_content = Input::get('comment');                
            $post->post_title   = Input::get('rating');
            $post->post_parent  = Input::get('pid');
            $post->post_name    = 'product-review';
            $post->post_type    = 'comment';
            $post->post_status  = 'pending';

            $post->save();   

            $data['my_review'] = $post;

            return view($this->view.'.customer.product-review', $data);
        } else {
            $post = $this->post->find($post->id);
            $post->forceDelete();            
        }               

    }

    //--------------------------------------------------------------------------

    public function reviews($id='')
    {
        $data['reviews'] = $this->post->site()
                                      ->where('post_type', 'comment')
                                      ->where('post_parent', $id)
                                      ->where('post_author', '!=', $this->user_id)
                                      ->orderBy('id', 'DESC')
                                      ->paginate(1);   

        return view($this->view.'.customer.product-reviews', $data);    
    }

    //--------------------------------------------------------------------------

    public function single($name='')
    {
        $data['user'] = $this->user;

        $data['info'] = $info = $this->post->site()
                                           ->where('post_type', 'product')
                                           ->where('post_name', $name)
                                           ->where('post_status', 'actived')
                                           ->first();


        $blocked = $this->post->site()
                               ->where('post_type', 'product_rental')
                               ->where('post_parent', $info->id)
                               ->where('post_status', 'actived')
                               ->where('post_name', '>', date('Y-m-d'))
                               ->get()
                               ->pluck('post_content', 'id')
                               ->toArray();
		$data['blocked'] = array(date('Y-m-d'));
		foreach ($blocked as $blk) {
				$data['blocked'] = array_merge($data['blocked'], json_decode($blk));
		}

		$blocked = array_values(array_unique($data['blocked']));

		$data['blocked'] = json_encode($blocked);

        if( $info ) {
            foreach ($info->postmetas as $postmeta) {
                $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
            }
        }   

        $data['reviews'] = $this->post->site()
                                      ->where('post_type', 'comment')
                                      ->where('post_parent', $info->id)
                                      ->where('post_author', '!=', $this->user_id)
                                      ->orderBy('id', 'DESC')
                                      ->paginate(10);                                       
        
        $select[] = DB::raw('post_title');
        $select[] = DB::raw('count(*) as count'); 
               
        $data['ratings'] = $this->post
                                      ->select($select)
                                      ->site()
                                      ->where('post_type', 'comment')
                                      ->where('post_parent', $info->id);                          
        
        $data['rate'] = $data['ratings']->groupBy('post_title')
                                        ->get()
                                        ->pluck('count', 'post_title')
                                        ->toArray();

        $data['my_review'] = $this->post->site()
                                      ->where('post_type', 'comment')
                                      ->where('post_parent', $info->id)
                                      ->where('post_author', $this->user_id)
                                      ->first(); 

        $cat = @$info->category ? implode('|', json_decode($info->category)) : 0;

        $data['rows'] = $this->post
                            ->select('posts.*', 'm1.meta_value as category')
                            ->from('posts')
                            ->join('postmeta AS m1', function ($join) use ($cat) {
                            $join->on('posts.id', '=', 'm1.post_id')
                                ->where('m1.meta_key', '=', 'category')
                                ->whereRaw("meta_value RLIKE {$cat}");
                            })->site()
                             ->where('posts.post_type', 'product')
                             ->where('posts.id', '!=', $info->id)
                             ->where('posts.post_status', 'actived')
                             ->orderBy('posts.id', 'DESC')
                             ->limit(8)
                             ->get()
                             ->chunk(4);

        $data['discount'] = discount_percentage(@$info->sale_price, @$info->regular_price);



        return view($this->view.'.single', $data);
    }

    //--------------------------------------------------------------------------

    public function update_delivery()
    {   
        $time = Input::get('time');
        $id = Input::get('id');

        if($time && $id) {
            $delivery_fee = delivery_fee($time)['amount'];

            $cart = Session::get('cart');

            $cart['orders'][$id]['delivery_time'] = $time;
            $cart['orders'][$id]['delivery_fee']  = $delivery_fee;

            $cart['delivery_fee']   = array_sum(data_get($cart, 'orders.*.delivery_fee'));   
            $cart['subtotal']       = array_sum(data_get($cart, 'orders.*.total_price'));
            $cart['total']          = ($cart['subtotal'] - @$cart['discount_fee']) + $cart['delivery_fee'];

            session(['cart' => $cart]); 

        }
    }

    //--------------------------------------------------------------------------

    public function add_to_cart()
    {   
        $data = array();
        
        // return json_encode( Input::all() );

        $product_id = Input::get('add-to-cart');
        $qty = Input::get('qty');

        $push = $this->shop->push_cart($product_id, $qty, 'add');

        if( \Request::header('width') <= 767 ) {
            $cart = session('cart');
            $data = [
                'quantity' => $cart['quantity'],
                'total' => amount_formatted($cart['total']),
            ];
            return json_encode($data);
        }

        return view($this->view.'.mini-cart', $data);
    }

    //--------------------------------------------------------------------------
    
    public function cart()
    {   
        $data['cart'] = $cart = session('cart');

        if( Input::get('_token') ) {

            if( Input::get('coupon_code') ) {
                return app('App\Http\Controllers\CouponController')->apply();
            }

            foreach(Input::get('qty') as $product_id => $qty) {
                $this->shop->push_cart($product_id, $qty, 'update');
            }
            return Redirect::back()
                           ->with('success', 'Cart has been updated!');
        }

        return view($this->view.'.cart', $data);
    }

    //--------------------------------------------------------------------------
      
    public function checkout($name='')
    {   

        $checkouts = session('checkout');

        if( $this->request->header('fill')  ) {
            $inputs = Input::except(['_token', 'payment_method', 'card_type', 'card_number', 'card_expiry', 'card_cvv', 'coupon_code', 'shipping']);
            session(['checkout' => $inputs]);
            return json_encode($inputs);
        }
        
        $data['cart']    = session('cart');
        $data['setting'] = $this->setting;
       
        $data['user'] = $user = Auth::User();
        if( $user ) {
            foreach ($user->usermetas as $usermeta) {
                $data['user'][$usermeta->meta_key] = $usermeta->meta_value;
            }
        }   

        if( Input::get('_token') )
        {

            $inputs = Input::except(['_token', 'status', 'same_as_billing', 'lang', 'email', 'password', 'coupon_code']);

            $rules = [
                'delivery_street_address_1' => 'required',
                'delivery_city'          => 'required',
                'delivery_zipcode'       => 'required',
                'delivery_country'       => 'required',
            ];

            if( Input::get('payment_method') == 'paymaya' ) {
                $card_expiry_rule = ['required',
                function ($attribute, $value, $fail) {
                    $date = explode('/', str_replace(' ', '', $value));
                    if( $date[0] < date('m') && $date[1] == date('y') || $date[1] < date('y') ) {
                        $fail('Credit card is expired.');
                    }
                }];

                $rules = $rules + [
                    'card_number'  => 'required',
                    'card_expiry'  => $card_expiry_rule,
                    'card_cvv'     => 'required',
                ];
            }

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }

            session(['checkout' => $inputs]);

            if( $inputs['payment_method'] == 'paypal' ) {
                return app('App\Http\Controllers\PaypalController')->payOrder();
            } elseif( $inputs['payment_method'] == 'paymaya' ) {
                $this->place_order(); 
            } elseif( $inputs['payment_method'] == 'bank_transfer' ) {
                $this->place_order(); 
            }
            
	        return Redirect::route($this->view.'.place-order');
        }
        
        return view($this->view.'.checkout', $data);
    }

    //--------------------------------------------------------------------------

    public function create_order() {

        $inputs = session('checkout');
        $cart = session('cart');

        $post_status = 'pending';        
        $order_status = 'Completed';
        if( in_array($inputs['payment_method'], ['paymaya', 'bank_transfer']) ) {
            $post_status = 'on-hold';
            $order_status = 'New';
        }

        $post = $this->post;
        $post->site_id      = $this->site_id;
        $post->post_author  = $this->user_id;
        $post->post_content = json_encode($cart);                
        $post->post_title   = '';
        $post->post_name    = $token = str_random(64);
        $post->post_type    = 'order';
        $post->post_status  = $post_status;

        if( $post->save() ) {
            array_forget($cart, 'orders');
            $inputs = $inputs + $cart;
            $inputs['date_ordered'] = date('Y-m-d');
            $inputs['time_ordered'] = date('H:i');
            $inputs['currency']     = $this->setting->get_setting('currency');
            $inputs['order_status'] = $order_status;
            $inputs['order_status_updated'] = date('Y-m-d H:i:s');

            foreach ($inputs as $meta_key => $meta_val) {
                $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
            }

			foreach (session('cart')['orders'] as $order) {
				$cart_order_data = [
					"site_id"      => 1,
					"post_author"  => $this->user_id,
					"post_content" => json_encode(dates_from_range($order['delivery_date'], $order['return_date'], 'Y-m-d')),
					"post_title"   => $order['delivery_date'],
					"post_status"  => 'actived',
					"post_name"    => $order['return_date'],
					"post_type"    => 'product_rental',
					"post_parent"  => $order['pid'],
					"created_at"   => date('Y-m-d H:i:s'),
					"updated_at"   => date('Y-m-d H:i:s'),
				];
				DB::table('posts')->insert($cart_order_data);
			}

        }

        $data = array(
            'id'    => $post->id,
            'token' => $token,
            'info'  => $post
        );

        session(['order_data' => $data]);

        return $data;
    }

    //--------------------------------------------------------------------------

    public function place_order() {

        $order_data   = session('order_data');
        $inputs       = session('checkout');
        $email_sent   = session('email_sent');
        $data['cart'] = $cart = session('cart');

        if( ! $order_data ) {
            $this->create_order();
            $order_data = session('order_data');
        }

        if( @$inputs['create_account'] ) {
            $user = $this->user;

            $user->site_id   = $this->site_id;
            $user->firstname = $inputs['firstname'];    
            $user->lastname  = $inputs['lastname'];    
            $user->username  = $inputs['username'];   
            $user->email     = $inputs['email_address'];                   
            $user->status    = 'actived';    
            $user->group     = 'customer';    
            $user->usermeta  = json_encode($inputs);   
            $user->password  = Hash::make( $inputs['password'] );  
            $user->save();

            foreach ($inputs as $meta_key => $meta_val) {
                $this->usermeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
            }

            $inputs['user_id'] = $user->id;
        }


        // BEGIN EMAIL CONFIRMATION jk
        $order_status = 'new_order';    
        $payment_details = 'Payment Method : '.payment_methods($inputs['payment_method'])['name'];

        if( in_array($inputs['payment_method'], ['paymaya', 'bank_transfer']) ) {
            $order_status = 'on_hold_order';
        }

        if( in_array($inputs['payment_method'], ['bank_transfer']) ) {
            $payment_details = '<p>'.payment_methods($inputs['payment_method'])['description'].'</p>'.view('frontend.partials.inc.bank-details');
        }

        $data['email'] = $this->post->where('post_type', 'email')
                                    ->where('post_name', $order_status)
                                    ->first();

        $billing_address = implode(' ', array(
            $inputs['delivery_floor'],
            @$inputs['delivery_unit_no'],
            $inputs['delivery_street_address_1'],
            $inputs['delivery_region'],
            $inputs['delivery_city'],
            $inputs['delivery_barangay'],
            $inputs['delivery_zipcode'],
            countries($inputs['delivery_country']))
        );

        $order_details = view('shop.inc.order', $data);

        $patterns = [
            '/\[name\]/'             => ucwords($inputs['firstname'].' '.$inputs['lastname']),
            '/\[site_title\]/'       => $this->setting->get_setting('site_title'),
            '/\[order_number\]/'     => $order_data['id'],
            '/\[order_details\]/'    => $order_details,
            '/\[payment_details\]/'  => $payment_details,
            '/\[email_address\]/'    => $inputs['email_address'],
            '/\[billing_address\]/'  => $billing_address,
            '/\[phone\]/'            => $inputs['phone'],
            '/\[order_date\]/'       => date_formatted(date('Y-m-d')),
            '/\[total\]/'            => amount_formatted($cart['total']),
        ];

        $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
        $data['site_title']  = $this->setting->get_setting('site_title');
        $data['admin_email'] = $this->setting->get_setting('admin_email');
        $data['subject']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_title);
        $data['user_email']  = $inputs['email_address'];

        if( ! $email_sent ) {
            Mail::send('emails.default', $data, function($message) use ($data) {
                $message->from($data['admin_email'], $data['site_title'])
                        ->to($data['user_email'])
                        ->subject( $data['subject'] );
            });
            session(['email_sent' => true]);            
        }

        // END EMAIL CONFIRMATION jk


        return Redirect::route($this->view.'.completed', $order_data['token']);

    }   	
   	
    //--------------------------------------------------------------------------

    public function completed($token='')
    {   

        $data['info'] = $info = $this->post->site()
                                           ->where('post_type', 'order')
                                           ->where('post_name', $token)
                                           ->firstOrFail();

        if( $info ) {
            foreach ($info->postmetas as $postmeta) {
                $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
            }
        } 

        $data['cart'] = json_decode($info->post_content);

        session()->forget(['cart', 'order_data', 'checkout', 'email_sent']);

        return view($this->view.'.completed', $data);
    }

    //--------------------------------------------------------------------------
   
    public function remove_item($id='')
    {   
        $cart = session('cart');
        array_forget($cart, 'orders.'.$id);

        $cart['quantity'] = array_sum(data_get($cart, 'orders.*.quantity'));
        $cart['subtotal'] = array_sum(data_get($cart, 'orders.*.total_price'));
        $cart['delivery_fee']   = array_sum(data_get($cart, 'orders.*.delivery_fee'));   
        $cart['total']    = ($cart['subtotal'] - @$cart['discount_fee']) + $cart['delivery_fee'];

        session(['cart' => $cart]);

        return Redirect::back()
                       ->with('success', 'Selected item has been removed.');
    }

    //--------------------------------------------------------------------------
    
    public function settings()
    {   
        $data = array();
        
        $data['label'] = trans('backend.shop');                                      

        $data['info'] = (object)$this->setting->site()->get()->pluck('value', 'key')->toArray();

        if ( Input::get('_token') ) 
        {   
            $inputs = Input::except(['_token', 'lang']);

            if( @$inputs['payment_methods'] ) {
                 $inputs['payment_method'] = @$inputs['payment_method'] ? @$inputs['payment_method'] : @$inputs['payment_methods'][0];  
            }

            $this->setting->update_metas($inputs);

            return Redirect::back()
                           ->with('success', trans('messages.changes_saved'));
        }

        return view($this->view_b.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function cart_totals()
    {    
        $data['cart'] = session('cart');

        return view($this->view.'.inc.total', $data);    
    }

    //--------------------------------------------------------------------------

    public function emails()
    {
        $data = array();
        
        $data['label']    = trans('backend.shop_emails');                                      
        $data['view']     = $this->view_b;
        $data['post']     = $this->post;                         

        $email = Input::get('email', 'new_order');

        $data['info'] = $info = $this->post->site()
                                           ->where('post_type', 'email')
                                           ->where('post_name', $email)
                                           ->first();

        if( Input::get('_token') )
        {
            $rules = [
                'subject'  => 'required',
                'message'  => 'required'
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $post = $info ? $info : $this->post;

            $post->site_id      = $this->site_id;
            $post->post_author  = $this->user_id;
            $post->post_content = Input::get('message');                
            $post->post_title   = Input::get('subject');
            $post->post_name    = $email;
            $post->post_type    = 'email';
            $post->post_status  = 'actived';

            if( $post->save() ) {

                return Redirect::back()
                               ->with('success', trans('messages.changes_saved'));
            } 
        }

        return view($this->view_b.'.emails', $data);
    }

    //--------------------------------------------------------------------------

    // URL: /api/check-orders

    public function check_orders()
    {    

        $order_status = [
            'New',
            'Approved',
            'Pending Unapproved',
            'Sent',
            'Reship ',
            'Verified',
            'Details problem ',
            'compensation',
            'pending reship',
            'Pending Payment',
            'Pending Shipping',
            'PSP Dispute',
            'Shipping',
            'Refund Failed',
        ];

        $date = date('Y-m-d H:i:s');

        $rows = $this->post
                       ->where('post_type', 'order')
                       ->select('posts.*', 'm1.meta_value as order_status', 'm2.meta_value as payment_method', 'm3.meta_value as zip')
						->from('posts')
						->join('postmeta AS m1', function ($join) use ($order_status) {
							$join->on('posts.id', '=', 'm1.post_id')
						         ->where('m1.meta_key', '=', 'order_status')
						         ->whereIn("m1.meta_value", $order_status);
						})
						->join('postmeta AS m2', function ($join) {
								$join->on('posts.id', '=', 'm2.post_id')
						         ->where('m2.meta_key', '=', 'payment_method')
						         ->where("m2.meta_value", 'gotopay');
						})
						->join('postmeta AS m3', function ($join) {
							$join->on('posts.id', '=', 'm3.post_id')
						         ->where('m3.meta_key', '=', 'billing_zipcode');
						})->get();

        foreach($rows as $row) {
            $data = $this->gotopay->check_order($row->id, $row->zip);  
            PostMeta::update_meta($row->id, 'order_status', $data->status);
            PostMeta::update_meta($row->id, 'order_status_updated', $date);
        }
        
        echo count($rows).' orders has been check on '.$date;
    }

    //--------------------------------------------------------------------------

}
