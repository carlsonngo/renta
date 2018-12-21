<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config, File;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;

class SettingController extends Controller
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
        $this->user       = $user;
        $this->usermeta   = $usermeta;
        $this->post       = $post;
        $this->postmeta   = $postmeta;
        $this->setting    = $setting;
        $this->request    = $request;

        $this->view = 'backend.settings';    

        $this->middleware(function ($request, $next) {
            $this->site_id = get_domain();
            $this->user_id = Auth::check() ? Auth::user()->id : 0;
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function emails()
    {
        $data = array();
        
        $data['label']    = trans('backend.email_template');                                      
        $data['view']     = $this->view;
        $data['post']     = $this->post;                         

        $email = Input::get('email', 'reservation');

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

        return view($this->view.'.emails', $data);
    }

    //--------------------------------------------------------------------------

    public function general()
    {
        $data = array();
        
        $data['label']    = trans('backend.general');                                      
        $data['view']     = $this->view;
        $data['post']     = $this->post;                         
        $data['setting']  = $this->setting;        

        $data['info'] = (object)$this->setting->site()->get()->pluck('value', 'key')->toArray();

        if ( Input::get('_token') ) 
        {   

            $inputs = Input::except(['_token', 'lang']);

            unset($inputs['bank_account'][0]);

            $inputs['site_language'] = $inputs['localization'] ? $inputs['site_language'] : 'en';

            $this->setting->update_metas($inputs);

            return Redirect::back()
                           ->with('success', trans('messages.changes_saved'));
        }

        return view($this->view.'.general', $data);
    }

    //--------------------------------------------------------------------------

    public function menus()
    {
        $data['label']     = trans('backend.menus');                                      
        $data['view']      = $this->view;
        $data['post']      = $this->post;     
        $data['setting']   = $this->setting;      
        $data['menu_name'] = Input::get('menu', 'header');

        $data['menus']['pages'] = $this->post->site()
                                    ->where('post_type', 'page')
        						    ->where('post_status', 'published')
                                    ->orderBy('id', 'DESC')
                                    ->get();

        $data['menus']['posts'] = $this->post->site()
                                    ->where('post_type', 'post')
        						    ->where('post_status', 'published')
                                    ->orderBy('id', 'DESC')
                                    ->get();

        if( $this->setting->get_setting('gallery_module') ) {                                    
            $data['menus']['galleries'] = $this->post->site()
                                        ->where('post_type', 'gallery')
                                        ->where('post_status', 'published')
                                        ->orderBy('id', 'DESC')
                                        ->get();
        }

        if( $this->setting->get_setting('events_module') ) {
            $data['menus']['events'] = $this->post->site()
                                        ->where('post_type', 'event')
                                        ->where('post_status', 'published')
                                        ->orderBy('id', 'DESC')
                                        ->get();
                           
            $data['menus']['event-category'] = $this->post->site()
                                        ->where('post_type', 'event-category')
                                        ->where('post_status', 'actived')
                                        ->orderBy('id', 'DESC')
                                        ->get();
        }        

        if( $this->setting->get_setting('shop_module') ) {
            $data['menus']['products'] = $this->post->site()
                                        ->where('post_type', 'product')
                                        ->where('post_status', 'actived')
                                        ->orderBy('id', 'DESC')
                                        ->get();

            $data['menus']['product-category'] = $this->post->site()
                                        ->where('post_type', 'product-category')
                                        ->where('post_status', 'actived')
                                        ->orderBy('id', 'DESC')
                                        ->get();
        }

        $data['info'] = $this->post->site()
                                   ->where('post_type', 'menu')
        						   ->where('post_name', $data['menu_name'])
                                   ->first();

        if ( Input::get('_token') ) 
        {   
            $inputs = Input::except(['_token', 'lang']);

            $inputs['menus'] = $inputs;

            $this->setting->update_metas($inputs);

            return Redirect::back()
                           ->with('success', trans('messages.changes_saved'));
        }

        return view($this->view.'.menus', $data);
    }

    //--------------------------------------------------------------------------

    public function menus_add() {
    	
    	if(Input::get('type') == 'custom-link') {
    		$menu = 'custom';
    		$data = Input::all();
    	} else {
    		$menu = 'post';
	        $data['rows'] = $this->post->whereIn('id', Input::get('menu-id'))->get();
    	}

        return view($this->view.'.menus.'.$menu, $data);
    }

    //--------------------------------------------------------------------------

    public function menus_save() {

        $id   = Input::get('id');
        $name = Input::get('name');

        $post = $id ? $this->post->find($id) : $this->post;

        $post->site_id      = $this->site_id;
        $post->post_author  = $this->user_id;
        $post->post_content = array_to_json(Input::get('data'));                
        $post->post_title   = menus($name);
        $post->post_name    = $name;
        $post->post_type    = 'menu';
        $post->post_status  = 'actived';

        $post->save();

    }

    //--------------------------------------------------------------------------

    public function css_theme() {

        $data['label']    = trans('backend.css_theme');                                      
        $data['view']     = $this->view;  
        $data['setting']  = $this->setting;      

        $data['css_theme'] = $this->setting->where('key', 'css_theme')->first();

        $filename = Input::get('theme', 'default');

        $asset = public_path('/css/themes/'.$filename.'.css');

        if( ! file_exists($asset) ) {
            return Redirect::route($this->view.'.css-theme');            
        }

        $data['date'] = date('Y-m-d H:i:s', filemtime($asset));

        $data['style'] = file_get_contents($asset);

        if ( Input::get('_token') ) {  
            file_put_contents($asset, Input::get('code') );

            $this->setting->update_metas(['css_theme' => Input::get('css_theme')]);

            minified_css('css/themes/', $filename);

            return Redirect::back()
                           ->with('success', trans('messages.updated', ['variable' => $data['label']]));
        }

        return view($this->view.'.theme-css', $data);
    }

    //--------------------------------------------------------------------------

    public function localization()
    {
        $data['label']   = trans('backend.localization');                                      
        $data['view']    = $this->view;
        $data['post']    = $this->post;     
        $data['setting'] = $this->setting;      
        $data['locale']  =  $this->setting->get_setting('site_language');

        
        $data['f'] = $f = Input::get('f', 'backend');

        $data['trans'] = \Lang::get($f, [], 'en');

        if ( Input::get('_token') ) {   
            $inputs = Input::except(['_token']);
            $resource_path = resource_path('lang/'.$inputs['lang'].'/'.$f.'.php');

            $data['langs'] = \Lang::get( $f, [], $inputs['lang'] );

            data_set($data['langs'], $inputs['key'], $inputs['val']);

            file_put_contents($resource_path, '<?php return '.var_export($data['langs'], true).';' );

        }

        return view($this->view.'.localization', $data);
    }

    //--------------------------------------------------------------------------

    public function localization_export() {

        $f = Input::get('f', 'backend');
        $langs = languages();
        $trans = \Lang::get($f, [], 'en');
        
        $rows = array();

        $i=0;
        foreach($trans as $tran_k => $tran_v) {
            $l = array();

            if( is_array($tran_v) ) {
                foreach($tran_v as $l_k => $l_v) {
                    foreach ($langs as $lang_k => $lang_v) {
                        $output = \Lang::get($f.'.'.$tran_k.'.'.$l_k, [], $lang_k);
                        if ( preg_match('#[^a-zA-Z0-9]#', str_replace(' ', '', $output)) ) {
                            $output = mb_convert_encoding($output, 'UCS-2LE', 'UTF-8');
                        }
                        $l['variable'] = $tran_k.'.'.$l_k;
                        $l[$lang_k] = $output;
                        $rows[$i.$l_k] = $l;
                    }                 
                }
            } else {
                foreach ($langs as $lang_k => $lang_v) {
                    $l['variable'] = $tran_k;
                    $output = \Lang::get($f.'.'.$tran_k, [], $lang_k);
                    if ( preg_match('#[^a-zA-Z0-9]#', str_replace(' ', '', $output)) ) {
                        $output = mb_convert_encoding($output, 'UCS-2LE', 'UTF-8');
                    }

                    $l[$lang_k] = $output;
                }
                $rows[$i] = $l;
            }

            $i++;
        }



        $date = date('Y-m-d');
        // output headers so that the file is downloaded rather than displayed
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.$f.'-'.$date.'.csv"');
         
        // do not cache the file
        header('Pragma: no-cache');
        header('Expires: 0');
         
        // create a file pointer connected to the output stream
        $file = fopen('php://output', 'w');

        // send the column headers
        $columns = array_values($langs);
        $columns = array_prepend($columns, 'Variable');
        fputcsv($file, $columns);
   
        // output each row of the data
        $data_new=array();

        foreach($rows as $row) {

            $data_new = $row;

            fputcsv($file, $data_new);
        }
 
    }

    //--------------------------------------------------------------------------

    public function localization_import() {
	    $f    = Input::get('f', 'backend');
		$file = Input::file('file');
        $filename = strtolower($file->getClientOriginalName());

        if( ! str_contains($filename, $f) ) {
            return Redirect::route('backend.settings.localization', query_vars())
                   ->with('error', trans('messages.csv_file_not_matched'));	
        }

		foreach (languages() as $lang_k => $lang_v) {
	        $trans[$lang_k] = \Lang::get($f, [], $lang_k);
		}

		$handle = fopen($file, "r");

		$i=0;
		while (($rows = fgetcsv($handle)) !== FALSE) {
			if( $i != 0) {
				$l=1;
				foreach (languages() as $lang_k => $lang_v) {
					$varriable = $rows[0];
					$data[$lang_k][$varriable] = $rows[$l];  

					if( data_get($trans[$lang_k], $varriable) ) {
						data_set($trans[$lang_k], $varriable, $rows[$l]);
					}
					$l++;                
				}
			} 
			$i++;
		}

		foreach (languages() as $lang_k => $lang_v) {
			$resource_path = resource_path('lang/'.$lang_k.'/'.$f.'.php');
			file_put_contents($resource_path, '<?php return '.var_export($trans[$lang_k], true).';' );
		}

        return Redirect::back()
                       ->with('success', trans('messages.updated', ['variable' => 'Localization settings']));
    }
    
    //--------------------------------------------------------------------------

    public function slider()
    {
        $data['label']   = trans('backend.slider');                                      
        $data['view']    = $this->view;
        $data['post']    = $this->post;     
        $data['setting'] = $this->setting;      
        $data['locale']  =  $this->setting->get_setting('site_language');

        $data['info'] = $post = $this->post->site()->where('post_name', 'home-slider')->first();

        if( Input::get('_token') )
        {

            $post = $post ? $post : $this->post;

            $post->site_id      = $this->site_id; 
            $post->post_author  = $this->user_id;
            $post->post_content = array_to_json(Input::get('slider'));                
            $post->post_title   = 'Home Slider';
            $post->post_name    = 'home-slider';
            $post->post_type    = 'slider';
            $post->post_status  = Input::get('status');
            $post->updated_at   = date('Y-m-d H:i:s');
            
            if( $post->save() ) {
                return Redirect::back()
                               ->with('success', trans('messages.updated', ['variable' => $data['label']]));
            } 
        }


        return view($this->view.'.slider', $data);
    }

    //--------------------------------------------------------------------------

}
