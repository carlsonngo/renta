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

class EventController extends Controller
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

        $this->view = 'frontend.events';

        $this->middleware(function ($request, $next) {
            $this->site_id = get_domain();
            $this->user_id = Auth::check() ? Auth::user()->id : 0;
            return $next($request);
        });

    }

    //--------------------------------------------------------------------------

    public function index()
    {        
        $data['view'] = $this->view;

        parse_str( query_vars(), $search );

        $data['date'] = Input::get('date_start', date('Y-m-d'));

        $date['start'] = Input::get('date_start', date('Y-m-d')).' '.date('H:i:s');
        $date['end'] = Input::get('date_start', date('Y-m-d')).' 24:00:00';

        $data['rows'] = $this->post
                ->select('posts.*', 'm1.meta_value as event_start')
                ->from('posts')
                ->join('postmeta AS m1', function ($join) use ($date) {
                $join->on('posts.id', '=', 'm1.post_id')
                    ->where('m1.meta_key', '=', 'event_start')
                    ->where('m1.meta_value', '>=', $date['start'])
                    ->where('m1.meta_value', '<=', $date['end']);
                })
                ->site()
                ->where('posts.post_type', 'event')
                ->orderBy('event_start', 'DESC')
                ->paginate(10);

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------
   
    public function calendar()
    {        
        $data['rows'] = [];

        return view($this->view.'.calendar', $data);
    }

    //--------------------------------------------------------------------------

    public function single($name ='')
    {        
        $data['module']   = 'bookings';
        $data['currency'] = $this->setting->get_setting('currency');

        parse_str( query_vars(), $search );

        $reserve['confirmed'] = 1;

        $data['registers'] = $this->post
                             ->search($reserve, [], ['confirmed'])
                             ->whereIn('post_type', ['reservation', 'ticket'])
                             ->orderBy('id', 'ASC')
                             ->get();


        $post_status[] = 'published';

        if( $this->user_id ) {
            $post_status[] = 'draft';
        }

        $data['info'] = $info = $this->post->site()
                                           ->where( 'post_name', $name )
                                           ->whereIn('post_status', $post_status)
                                           ->whereIn('post_type', ['post', 'page', 'event'])
                                           ->firstOrFail();
 
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        if( Input::get('_token') ) {

            if( Input::get('form') == 'confirm' ) {
                $rules = [
                    'verification_code'  => 'required|exists:posts,post_name'
                ];
            } else {
                $rules = [
                    'nickname'  => 'required',
                    'name'      => 'required',
                    'email'     => 'required',
                    'comment'   => 'required',
                    'captcha'   => 'required|same:recaptcha',
                ];
            }

            $messsages = ['captcha.same' => 'Security captcha code does not match.'];

            $validator = Validator::make(Input::all(), $rules, $messsages);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }

            if( Input::get('form') == 'confirm' ) { 
                return $this->verify(Input::get('verification_code'));
            }

            $inputs = Input::except(['_token', 'recaptcha', 'captcha', 'comment', 'email', 'lang']);

            $post = $this->post;
            $post->site_id      = $this->site_id;
            $post->post_author  = $this->user_id;
            $post->post_content = Input::get('comment');                
            $post->post_title   = Input::get('email');
            $post->post_name    = strtoupper(str_random(6));
            $post->post_type    = $info->event_type;
            $post->post_status  = 'pending';
            $post->created_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {

                $inputs['event_id']   = $info->id;
                $inputs['date_start'] = $info->date_start;
                $inputs['time_start'] = $info->time_start;
                $inputs['currency']   = $this->setting->get_setting('currency');

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
                }

                // BEGIN EMAIL CONFIRMATION
                $inputs = Input::all();

                $attendees = '';
                $persons_count = 0;
                if( $inputs['attendee'] ) {
                    foreach($inputs['attendee'] as $att_k => $att_v) {
                        $attendees .= '<b>'.ucwords($att_k).'</b> : '.$att_v['count'].'<br>';
                        $persons_count += $att_v['count'];
                    }                    
                }

                $data['email'] = $this->post->where('post_type', 'email')
                                            ->where('post_name', $info->event_type) 
                                            ->first();
                $patterns = [
                    '/\[nickname\]/'          => ucwords($inputs['nickname']),
                    '/\[name\]/'              => ucwords($inputs['name']),
                    '/\[email_address\]/'     => $inputs['email'],
                    '/\[event_title\]/'       => $info->post_title,
                    '/\[persons\]/'           => $attendees,
                    '/\[persons_count\]/'     => $persons_count,
                    '/\[login_code\]/'        => $post->post_name,
                    '/\[ticket_code\]/'       => $post->post_name,
                    '/\[bank_details\]/'      => view('frontend.partials.inc.bank-details'),           
                    '/\[ticket_link\]/'       => route('frontend.events.ticket', $post->post_name),
                    '/\[date_created\]/'      => date_formatted($post->created_at),
                    '/\[event_date\]/'        => date_formatted($info->date_start),
                    '/\[event_time\]/'        => time_formatted($info->time_start),
                    '/\[confirmation_code\]/' => $post->post_name,
                    '/\[confirmation_link\]/' => route('frontend.events.verify', $post->post_name),
                    '/\[total\]/'             => amount_formatted(Input::get('total')),
                ];


                $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
                $data['site_title']  = $this->setting->get_setting('site_title');
                $data['admin_email'] = $this->setting->get_setting('admin_email');
                $data['subject']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_title);
                $data['user_email']  = $inputs['email'];

                Mail::send('emails.default', $data, function($message) use ($data)
                {
                    $message->from($data['admin_email'], $data['site_title'])
                            ->to($data['user_email'])
                            ->subject( $data['subject'] );
                });
                // END EMAIL CONFIRMATION 

                return Redirect::route($this->view.'.single', [$name, 'form' => 'confirm', 'lang' => Input::get('lang')])
                               ->with('registration_success', 'The application has been saved!');
            } 
        }


        return view('frontend.events.single', $data);  

    }

    // https://github.com/barryvdh/laravel-dompdf
    //--------------------------------------------------------------------------

    function ticket($code ='') {
        $data['info'] = $info = $this->post->site()
                               ->where('post_name', $code)
                               ->whereIn('post_type', ['reservation', 'ticket'])
                               ->firstOrFail();
        if( $info ) {
            foreach ($info->postmetas as $postmeta) {
                $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
            }
        }   

        $data['event'] = $event = $this->post->find($info->event_id);
        if( $event ) {
            foreach ($event->postmetas as $postmeta) {
                $data['event'][$postmeta->meta_key] = $postmeta->meta_value;
            }
        }  

       // return view('backend.bookings.ticket', $data);
        $pdf = \PDF::loadView('backend.bookings.ticket', $data);
        $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();

    }
    
    //--------------------------------------------------------------------------

    function verify($code ='') {
        $confirm = $this->post->site()
                               ->where('post_name', $code)
                               ->whereIn('post_type', ['reservation', 'ticket'])
                               ->firstOrFail();

        if( $confirm ) {
            foreach ($confirm->postmetas as $postmeta) {
                $confirm[$postmeta->meta_key] = $postmeta->meta_value;
            }
        }   

        $event = $this->post->find($this->postmeta->get_meta($confirm->id, 'event_id'));

        $this->postmeta->update_meta($confirm->id, 'confirmed', 1);

        // BEGIN EMAIL CONFIRMATION
        $attendees = '';
        $persons_count = 0;
        if( @$confirm->attendee ) {
            foreach(json_decode($confirm->attendee, true) as $att_k => $att_v) {
                $attendees .= '<b>'.ucwords($att_k).'</b> : '.$att_v['count'].'<br>';
                $persons_count += $att_v['count'];
            }                    
        }

        $event_type = $confirm->post_type=='ticket' ? 'pay-ticket' : 'confirm-reservation';

        $data['email'] = $this->post->where('post_type', 'email')
                                    ->where('post_name', $event_type) 
                                    ->first();

        $patterns = [
            '/\[nickname\]/'          => ucwords($confirm->nickname),
            '/\[name\]/'              => ucwords($confirm->name),
            '/\[email_address\]/'     => $confirm->email,
            '/\[event_title\]/'       => $event->post_title,
            '/\[persons\]/'           => $attendees,
            '/\[persons_count\]/'     => $persons_count,
            '/\[login_code\]/'        => $code,
            '/\[ticket_code\]/'       => $code,
            '/\[bank_details\]/'      => view('frontend.partials.inc.bank-details'),           
            '/\[ticket_link\]/'       => route('frontend.events.ticket', $code),
            '/\[date_created\]/'      => date_formatted($confirm->created_at),
            '/\[event_date\]/'        => date_formatted($event->date_start),
            '/\[event_time\]/'        => time_formatted($event->time_start),
            '/\[confirmation_code\]/' => $code,
            '/\[confirmation_link\]/' => route('frontend.events.verify', $code),
            '/\[total\]/'             => amount_formatted($confirm->total),
        ];

        $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
        $data['site_title']  = $this->setting->get_setting('site_title');
        $data['admin_email'] = $this->setting->get_setting('admin_email');
        $data['subject']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_title);
        $data['user_email']  = $confirm->post_title;

        Mail::send('emails.default', $data, function($message) use ($data)
        {
            $message->from($data['admin_email'], $data['site_title'])
                    ->to($data['user_email'])
                    ->subject( $data['subject'] );
        });
        // END EMAIL CONFIRMATION 

        return Redirect::route($this->view.'.single', [$event->post_name, 'form' => 'confirm'])
                       ->with('success', 'Your application for this event has been confirmed!');          
    }
    
    //--------------------------------------------------------------------------
}
