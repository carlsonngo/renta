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

class BookingController extends Controller
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

        $this->single    = 'Booking';
        $this->label     = 'Bookings';  

        $this->view      = 'frontend.bookings';
        $this->view_b    = 'backend.bookings';
        $this->post_type = Input::get('post_type');

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
        $data['view']      = $this->view_b;
        $data['view_p']    = 'backend.posts';
        $data['post']      = $this->post;
        $data['post_type'] = $this->post_type;
        $post_type = array('reservation', 'ticket');
        $data['module']    = 'bookings';

        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->search($search)
                             ->whereIn('post_type', $post_type)
                             ->orderBy(Input::get('sort', 'id'), Input::get('order', 'DESC'))
                             ->paginate(10);

        $data['count'] = $this->post
                              ->search($search)
                              ->whereIn('post_type', $post_type)
                              ->count();
        
        unset($search['type']);
                                      
        $data['all'] = $this->post->search($search)->whereIn('post_type', $post_type)->count();

        $data['trashed'] = $this->post->withTrashed()
                                      ->search($search)
                                      ->whereIn('post_type', $post_type)
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

        return view($this->view_b.'.index', $data);
    }

    //--------------------------------------------------------------------------
   
    public function report()
    {

        $data['single']    = $this->single;                                      
        $data['label']     = $this->label; 
        $data['view']      = $this->view_b;
        $data['view_p']    = 'backend.posts';
        $data['post']      = $this->post;
        $data['post_type'] = $this->post_type;
        $post_type = array('reservation', 'ticket');
        $data['module']    = 'bookings';

        parse_str( query_vars(), $search );

        $select[] = DB::raw("DATE_FORMAT(meta_value, '%Y-%m') date");
        $select[] = DB::raw('count(*) as count');

        $reservations = $this->post->select($select)
					        ->where('post_type', 'reservation')
	                        ->join("postmeta AS md", function ($join) use ($search) {
	                            $join->on("posts.id", '=', "md.post_id")
	                                 ->where("md.meta_key", '=', 'date_start');
	                        })
	                        ->groupBy('date')
	                        ->get()
	                        ->pluck('count', 'date')
	                        ->toArray();

		foreach (range(1, 12) as $month) {
			$data['reservations'][] =  $reservations[date('Y-').sprintf('%02d', $month)] ?? 0;
		}      
		$data['reservations'] = $reservations ? $data['reservations'] : array();  

        $ticket_orders = $this->post->select($select)
					        ->where('post_type', 'ticket')
	                        ->join("postmeta AS md", function ($join) use ($search) {
	                            $join->on("posts.id", '=', "md.post_id")
	                                 ->where("md.meta_key", '=', 'date_start');
	                        })
	                        ->groupBy('date')
	                        ->get()
	                        ->pluck('count', 'date')
	                        ->toArray();

		foreach (range(1, 12) as $month) {
			$data['ticket_orders'][] =  $ticket_orders[date('Y-').sprintf('%02d', $month)] ?? 0;
		}   
		$data['ticket_orders'] = $ticket_orders ? $data['ticket_orders'] : array();  

        $reserved = $this->post->select($select)
					        ->where('post_type', 'reservation')
	                        ->join("postmeta AS md", function ($join) use ($search) {
	                            $join->on("posts.id", '=', "md.post_id")
	                                 ->where("md.meta_key", '=', 'date_start');
	                        })
	                        ->where('post_status', 'completed')
	                        ->groupBy('date')
	                        ->get()
	                        ->pluck('count', 'date')
	                        ->toArray();

		foreach (range(1, date('m')) as $month) {
			$data['reserved'][] =  $reserved[date('Y-').sprintf('%02d', $month)] ?? 1;
		}   
		$data['reserved'] = $reserved ? $data['reserved'] : array();  

        $ticket = $this->post->select($select)
					        ->where('post_type', 'ticket')
	                        ->join("postmeta AS md", function ($join) use ($search) {
	                            $join->on("posts.id", '=', "md.post_id")
	                                 ->where("md.meta_key", '=', 'date_start');
	                        })
	                        ->where('post_status', 'completed')
	                        ->groupBy('date')
	                        ->get()
	                        ->pluck('count', 'date')
	                        ->toArray();

		foreach (range(1, date('m')) as $month) {
			$data['ticket_sold'][] =  $ticket[date('Y-').sprintf('%02d', $month)] ?? 0;
		}   
		$data['ticket_sold'] = $ticket ? $data['ticket_sold'] : array();  
    
        return view($this->view_b.'.report', $data);
    }

    //--------------------------------------------------------------------------

    public function calendar()
    {

        $data['single']    = 'Calendar';                                                                         
        $data['label']     = $this->label; 
        $data['view']      = $this->view_b;
        $data['view_p']    = 'backend.posts';
        $data['post']      = $this->post;
        $data['post_type'] = $this->post_type;
        $post_type         = array('reservation', 'ticket');
        $data['events']    = array();
        $data['module']    = 'bookings';

        $data['date'] = Input::get('y', date('Y')).'-'.Input::get('m', date('m'));

        parse_str( query_vars(), $search );

        $select[] = "posts.*";
        $select[] = "md.meta_value as date";

        $rows = $this->post->select($select)
                        ->join("postmeta AS md", function ($join) use ($search) {
                            $join->on("posts.id", '=', "md.post_id")
                                 ->where("md.meta_key", '=', 'date_start')
                                 ->where("md.meta_value", '>=', date('Y-m-d'));
                        })->whereIn('post_type', $post_type)
                          ->get();

        foreach($rows as $row) {

            $postmeta = get_meta( $row->postMetas()->get() );

            $data['events'][] = array(
                'title'     => event_type($row->post_type) .' by '.@$postmeta->name,
                'start'     => @$postmeta->date_start,
                'url'       => route($this->view_b.'.view', $row->id),
                'color'     => $row->post_type == 'reservation' ? '#E91E63':'#3F51B5',
                'textColor' => '#fff'
            );
        }

        $data['count'] = $this->post
                              ->search($search)
                              ->whereIn('post_type', $post_type)
                              ->count();
                       
        return view($this->view_b.'.calendar', $data);
    }

    //--------------------------------------------------------------------------
  
    public function view($id='')
    {                                                                       
        $data['label']     = $this->label; 
        $data['view']      = $this->view_b;
        $data['post']      = $this->post;
        $data['module']    = 'bookings';
        
        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        $data['event'] = $event = $this->post->find( $info->event_id );
        foreach ($event->postmetas as $postmeta) {
            $data['event'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        $data['notes'] = $this->post
                             ->where('post_type', 'order-note')
                             ->where('post_parent', $id)
                             ->get();

        if( Input::get('_token') )
        {
            $rules = [
                'nickname'  => 'required',
                'name'      => 'required',
                'email'     => 'required',
            ];

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::except(['_token', 'comment', 'email', 'lang', 'status']);

            $post = $this->post->find($id);
            $post->site_id      = $this->site_id;
            $post->post_content = Input::get('comment');                
            $post->post_title   = Input::get('email');
            $post->post_status  = Input::get('status');

            if( $post->save() ) {

                $inputs['date_start'] = date_formatted_b(Input::get('date_start'));
                $inputs['time_start'] = Input::get('time_start');

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
                }

                if( $info->post_status != 'paid' && Input::get('status') == 'paid' && $info->post_type == 'ticket' ) {

                    // BEGIN EMAIL CONFIRMATION
                    $attendees = '';
                    $persons_count = 0;
                    if( @$info->attendee ) {
                        foreach(json_decode($info->attendee, true) as $att_k => $att_v) {
                            $attendees .= '<b>'.ucwords($att_k).'</b> : '.$att_v['count'].'<br>';
                            $persons_count += $att_v['count'];
                        }                    
                    }

                    $code = $info->post_name;

                    $data['email'] = $this->post->where('post_type', 'email')
                                                ->where('post_name', 'paid-ticket') 
                                                ->first();

                    $patterns = [
                        '/\[nickname\]/'          => ucwords($info->nickname),
                        '/\[name\]/'              => ucwords($info->name),
                        '/\[email_address\]/'     => $info->email,
                        '/\[event_title\]/'       => $event->post_title,
                        '/\[persons\]/'           => $attendees,
                        '/\[persons_count\]/'     => $persons_count,
                        '/\[login_code\]/'        => $code,
                        '/\[ticket_code\]/'       => $code,
                        '/\[bank_details\]/'      => view('frontend.partials.inc.bank-details'),           
                        '/\[ticket_link\]/'       => route('frontend.events.ticket', $code),
                        '/\[date_created\]/'      => date_formatted($info->created_at),
                        '/\[event_date\]/'        => date_formatted($event->date_start),
                        '/\[event_time\]/'        => time_formatted($event->time_start),
                        '/\[confirmation_code\]/' => $code,
                        '/\[confirmation_link\]/' => route('frontend.events.verify', $code),
                        '/\[total\]/'             => amount_formatted($info->total),
                    ];

                    $data['content']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_content);
                    $data['site_title']  = $this->setting->get_setting('site_title');
                    $data['admin_email'] = $this->setting->get_setting('admin_email');
                    $data['subject']     = preg_replace(array_keys($patterns), $patterns, $data['email']->post_title);
                    $data['user_email']  = $info->post_title;

                    Mail::send('emails.default', $data, function($message) use ($data)
                    {
                        $message->from($data['admin_email'], $data['site_title'])
                                ->to($data['user_email'])
                                ->subject( $data['subject'] );
                    });
                    // END EMAIL CONFIRMATION 

                }

                return Redirect::back()
                               ->with('success', trans('messages.changes_saved'));
            } 

        }
        
        return view($this->view_b.'.view', $data);
    }

    //--------------------------------------------------------------------------
  
    public function add($id='')
    {                                                                 
        $data['label']     = $this->label; 
        $data['view']      = $this->view_b;
        $data['post']      = $this->post;
        $data['currency']  = $this->setting->get_setting('currency');

        $data['info'] = $info = $this->post->site()
                                           ->where( 'id', $id )
                                           ->where('post_status', 'published')
                                           ->where('post_type', 'event')
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
                    'email'     => 'required|email'
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
            $post->post_status  = Input::get('status');

            if( $post->save() ) {

                $inputs['event_id']   = $info->id;
                $inputs['date_start'] = $info->date_start;
                $inputs['time_start'] = $info->time_start;
                $inputs['currency']   = $this->setting->get_setting('currency');

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
                }

                return Redirect::route($this->view_b.'.view', $post->id)
                               ->with('success', 'The application has been saved!');
            } 
        }
     
        return view($this->view_b.'.add', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {

        $this->post->findOrFail($id)->delete();
        return Redirect::route($this->view_b.'.index', query_vars())
                       ->with('success', trans('messages.trashed', ['variable' => strtolower($this->single)]));
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
