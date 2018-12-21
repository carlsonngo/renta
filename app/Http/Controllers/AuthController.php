<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Setting;
use App\Post;
use App\PostMeta;



class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $user;
    protected $usermeta;
    protected $setting;
    protected $post;
    protected $postmeta;


    public function __construct(User $user, UserMeta $usermeta, Setting $setting, Post $post, PostMeta $postmeta)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->setting  = $setting;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->site_id  = get_domain();
    }

    //--------------------------------------------------------------------------

    public function login()
    {

        if( Auth::check() ) {
            $auth = Auth::user();
            return Redirect::route('backend.general.dashboard');
        }

        if(Input::get('_token')) {

            $insertRules = [
                'email'    => 'required',
                'password' => 'required',
            ];

            $validator = Validator::make(Input::all(), $insertRules);

            if($validator->passes()) {

                $field = filter_var(Input::get('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
                $remember = (Input::has('remember')) ? true : false;
                
                $credentials = [
                    $field     => Input::get('email'),
                    'password' => Input::get('password'),       
                ];

                if(Auth::attempt($credentials, $remember)) {               
                    $auth = Auth::user();
                    if( $auth ) {
                        foreach ($auth->usermetas as $usermeta) {
                            $checkouts[$usermeta->meta_key] = $usermeta->meta_value;
                        }
                    }   

                    Session::put('user_id', $auth->id);
                    $this->usermeta->update_meta($auth->id, 'last_login', date('Y-m-d H:i:s'));                
                    
                    $route = 'backend.general.dashboard';
                    
                    if( $auth->group == 'customer' ) {
                        session(['checkout' => $checkouts]);
                        $route = Input::get('intended', route('shop.customer.index'));
                        return Redirect::to($route)->with('success', 'Welcome <b>'.$auth->firstname.'</b>!');
                    }
                    
                    return Redirect::route($route)->with('success', 'Welcome <b>'.$auth->firstname.'</b>!');
                } 

                return Redirect::route('login', query_vars())
                               ->with('error','Invalid email or password')
                               ->withInput();
            }

            return Redirect::route('login', query_vars())
                           ->withErrors($validator)
                           ->withInput(); 
        }

        return view('auth.login');
    }

    //--------------------------------------------------------------------------

    public function register()
    {
        if( Auth::check() ) {
            $auth = Auth::user();
            return Redirect::to('/');
        }

        $data = $rules = array();

        $data['step'] = $step = Input::get('step', 1);
        $inputs = Input::except(['_token', 'step', 'save', 'government_id', 'bank_statement']);

        $data['memberships'] = $this->post->where('post_type', 'membership')
                                          ->where('post_status', 'actived')
                                          ->orderBy('post_order', 'ASC')
                                          ->get();

        if( Input::get('_token') )
        {
            if( $step == 2 ) {
                // set files
                $sess = session('step-'.$step);
                foreach(['government_id', 'bank_statement'] as $req) {
                    $inputs[$req]['data']   = @$sess[$req]['data'];                
                    $inputs[$req]['base64'] = @$sess[$req]['base64'];                
                    $inputs[$req]['ext']    = @$sess[$req]['ext'];  
                    if( Input::hasFile($req) ) {
                        $file = Input::file($req);
                        $ext  = $file->getClientOriginalExtension();
                        $filename = $file->getClientOriginalName();
                        $file_tmp = $_FILES[$req]['tmp_name'];
                        $file_data = file_get_contents( $file_tmp );
                        $inputs[$req]['data'] = 'data:image/' . $ext . ';base64,' . base64_encode($file_data);                
                        $inputs[$req]['base64'] = $file_data;                
                        $inputs[$req]['ext'] = $ext;   
                    }
                } 
            }

            session(['step-'.$step => $inputs]);

            if( Input::get('save') ) {
                return Redirect::route('auth.register', ['step' => $step-1]);                
            }

            if( $step == 1 ) {
                return Redirect::route('auth.register', ['step' => 2]);                
            }

            if( $step == 2 ) {
                $rules = [
                    'email'                     => 'required|email|max:64|unique_post:email,'.$this->site_id,
                    'firstname'                 => 'required|min:1|max:32',
                    'lastname'                  => 'required|min:1|max:32',
                    "landline"                  => 'required',
                    "mobile"                    => 'required',
                    "birthday"                  => 'required',
                    "home_unit_no"              => 'required',
                    "home_street_address_1"     => 'required',
                    "home_country"              => 'required',
                    "home_region"               => 'required',
                    "home_city"                 => 'required',
                    "home_barangay"             => 'required',
                    "home_zipcode"              => 'required',
                ];             


                $delivery = [
                    "delivery_unit_no"          => 'required',
                    "delivery_street_address_1" => 'required',
                    "delivery_country"          => 'required',
                    "delivery_region"           => 'required',
                    "delivery_city"             => 'required',
                    "delivery_barangay"         => 'required',
                    "delivery_zipcode"          => 'required',
                ];

                if( $inputs['same_as_home'] ) {
                    foreach(array_keys($delivery) as $add) {
                        $home = str_replace('delivery_', 'home_', $add);
                        $inputs[$add] = $inputs[$home];
                    }      
                    $inputs['delivery_street_address_2'] = $inputs['home_street_address_2'];    
                } else {
                    $rules = $rules + $delivery;     
                }
            }

            if( $step == 3 ) {
                $rules = [
                    "gender"                   => 'required',
                    "how_did_you_hear_from_us" => 'required',
                ];                      
            }

            Validator::extend('unique_post', function ($attribute, $value, $param, $validator) {
                $user = $this->user->where($param[0], $value)
                                   ->where('site_id', $param[1])
                                   ->exists();

                return $user ? false : true;
            });

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }

            if( $step == 2 ) {
                session(['steped-2' => 1]);
                return Redirect::route('auth.register', ['step' => 3]);     
            }

            if( $step == 3 ) {

                $inputs = session('step-1') + session('step-2') + session('step-3');

                $government_id  = $inputs['government_id'];
                $bank_statement = $inputs['bank_statement'];

                unset($inputs['government_id']);
                unset($inputs['bank_statement']);

                $user = $this->user;

                $user->firstname = $inputs['firstname'];
                $user->lastname  = $inputs['lastname'];
                $user->username  = '';
                $user->email     = $inputs['email'];
                $user->status    = 'pending';
                $user->group     = 'customer';
                $user->site_id   = $this->site_id;
                $user->verify_token = $token = str_random(64);
                $user->password  = '';  
                $user->usermeta  = json_encode( $inputs );   

               if( $user->save() ) {

                    // BEGIN EMAIL CONFIRMATION 
                    $data['email'] = $this->post->where('post_type', 'email')
                                                ->where('post_name', $inputs['membership'].'-registration')
                                                ->first();

                    $patterns = [
                        '/\[firstname\]/'         => ucwords($inputs['firstname']),
                        '/\[lastname\]/'          => ucwords($inputs['lastname']),
                        '/\[site_title\]/'        => $this->setting->get_setting('site_title'),
                        '/\[membership_type\]/'   => $inputs['membership'],
                        '/\[email_address\]/'     => $inputs['email'],
                        '/\[date_register\]/'     => date_formatted(date('Y-m-d')),
                        '/\[login_url\]/'         => route('auth.login'),
                        '/\[confirm_url\]/'       => route('auth.verify', $token),
                    ];

                    $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
                    $data['site_title']  = $this->setting->get_setting('site_title');
                    $data['admin_email'] = $this->setting->get_setting('admin_email');
                    $data['subject']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_title);
                    $data['user_email']  = $inputs['email'];

                    Mail::send('emails.default', $data, function($message) use ($data) {
                        $message->from($data['admin_email'], $data['site_title'])
                                ->to($data['user_email'])
                                ->subject( $data['subject'] );
                    });
                    // END EMAIL CONFIRMATION

                    unset($inputs['firstname']);
                    unset($inputs['lastname']);
                    unset($inputs['email']);


                    $id = $user->id;   

                    foreach ($inputs as $meta_key => $meta_val) {
                        $this->usermeta->update_meta($user->id, $meta_key, array_to_json($meta_val));
                    }

                    // Upload files
                    $path = 'uploads/'.$this->site_id.'/users/'.$id;
                    if( ! file_exists($path) ) mkdir($path, 0755,true);
                    
                    if( $government_id['base64'] ) {
                        file_put_contents($path.'/government_id.'.$government_id['ext'], $government_id['base64']); 
                    }

                    if( $bank_statement['base64'] ) {
                        file_put_contents($path.'/bank_statement.'.$bank_statement['ext'], $bank_statement['base64']); 
                    }

                    // clear session
                    session()->forget(['step-1', 'step-2', 'step-3', 'steped-2']);

                    return Redirect::route('auth.register', ['step' => 'completed']);
               } 
            }
        }

        if( ! session('step-1') && $step == 2 ) {
           return Redirect::route('auth.register', ['step' => 1]);      
        }
        if( ! session('steped-2') && $step == 3 ) {
           return Redirect::route('auth.register', ['step' => 2]);      
        }

        return view('auth.register.index', $data);
    }

    //--------------------------------------------------------------------------
   
    public function verify($token)
    {
        $data['info'] = $u = $this->user->where('verify_token', $token)->firstOrFail();        

        if(Input::get('_token')) {

            $insertRules = [
                'password' => 'required',
            ];

            $validator = Validator::make(Input::all(), $insertRules);

            if($validator->passes()) {

                $u->password = Hash::make(Input::get('password'));
                $u->verify_token = NULL;

                if( $u->save() ) {              
                    $user_id = $u->id;
                    
                    Auth::loginUsingId($u->id);
                    Session::put('user_id', $user_id);

                    $this->usermeta->update_meta($user_id, 'last_login', date('Y-m-d H:i:s'));     

                    return Redirect::route('shop.customer.index')
                                   ->with('success','You have successfully confirmed you account.');

                } 

            }
            
            return Redirect::back()
                           ->withErrors($validator)
                           ->withInput(); 

        }

        return view('auth.confirm', $data);
    }
    
    //--------------------------------------------------------------------------

    public function logout()
    {
        Auth::logout();
        Session::flash('success','You are now logged out!');
        return Redirect::route('login');
    }
    
    //--------------------------------------------------------------------------

    public function forgotPassword($token ='')
    {

        if( Auth::check() ) {
            $auth = Auth::user();

            $route = 'backend.general.dashboard';
            if( $auth->group == 'customer' ) {
                $route = 'shop.customer.index';
            }
                        
            return Redirect::route($route);
        }

        $data['token'] = $token;
        
        if($token) {

            $u = $this->user->where('forgot_password_token', $token)->first();

            if(!$u) return Redirect::route('auth.login');

            if(Input::get('_token') ) {

                $validator = Validator::make(Input::all(), User::$newPassword);
    
                if($validator->passes()) {

                    $u->password = Hash::make(Input::get('new_password'));
                    $u->forgot_password_token = NULL;

                    if( $u->save() ) {              
                        $user_id = $u->id;
                        
                        Auth::loginUsingId($u->id);
                        Session::put('user_id', $user_id);

                        $this->usermeta->update_meta($user_id, 'last_login', date('Y-m-d H:i:s'));     

                        $route = 'backend.general.dashboard';
                        if( $u->group == 'customer' ) {
                            $route = 'shop.customer.index';
                        }

                        return Redirect::route($route)
                                       ->with('success','You have successfully changed your password.');

                    } 
                } else {
                        
                    return Redirect::back()
                                   ->withErrors($validator)
                                   ->withInput();
                }
            }

        } else {

            if(Input::get('op') ) {

                $validator = Validator::make(Input::all(), User::$forgotPassword);
    
                if($validator->passes()) {

                    $token = str_random(64);
                    $email = Input::get('email');

                    $u = $this->user->where('email', $email)->where('status', 'actived')->first();

                    if( $u ) {              

                        $u->forgot_password_token = $token;
                        $u->save();

                        $data['name']      = ucwords( $u->firstname );
                        $data['email']     = $u->email;
                        $data['token_url'] = URL::route('auth.forgot-password', $u->forgot_password_token);
                        $data['base_url']  = URL::route('auth.login');
                        $data['site_name'] = $site_name = ucwords($this->setting->get_setting('site_title'));

                        $data['email_support'] = $this->setting->get_setting('admin_email');
                        $data['email_title']   = $site_name.' Support';
                        $data['email_subject'] = $site_name.' Forgotten Password!';

                        Mail::send('emails.forgot-password', $data, function($message) use ($data)
                        {
                            $message->from($data['email_support'], $data['email_title']);
                            $message->to($data['email'], $data['name'])->subject($data['email_subject']);
                        });

                        return Redirect::route('auth.forgot-password')
                                       ->with('success','Forgot password link has been sent to your email address. Please check your inbox or spam folder.');
                    } 

                    return Redirect::route('auth.forgot-password')
                                   ->with('warning','Sorry, Your email address has been deactivated! Please contact your administrator.');

                } 
                    
                return Redirect::route('auth.forgot-password')
                               ->withErrors($validator)
                               ->withInput();

            }



        }

        return view('auth.forgot-password', $data);
    }

    //--------------------------------------------------------------------------




}
