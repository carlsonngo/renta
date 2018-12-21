<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config, DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;

class ProductController extends Controller
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

	    $this->view      = 'backend.shop.products';
        $this->post_type = 'product';
        $this->single    = trans('backend.product');
        $this->label     = trans('backend.products');  

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
        $data['module']    = 'products';
    
        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->site()
                             ->where('post_type', $this->post_type)
                             ->search($search)
                             ->orderBy(Input::get('sort', 'id'), Input::get('order', 'DESC'))
                             ->paginate(10);

        $data['count'] = $this->post
                              ->site()
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

            if( in_array(Input::get('action'), ['actived', 'inactived'])) {

                foreach( Input::get('ids') as $id ) {
                    $user = Post::find($id)->update(['post_status' => Input::get('action')]);
                }
                return Redirect::back()
                               ->with('success', trans('messages.updated', ['variable' => ucfirst($this->single)]));
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
        
        $data['lang'] = $this->setting->get_setting('site_language');
        $data['currency'] = $this->setting->get_setting('currency');

        $data['memberships'] = $this->post->where('post_type', 'membership')
                                          ->where('post_status', 'actived')
                                          ->orderBy('post_order', 'ASC')
                                          ->get();

        if( Input::get('_token') )
        {
            $title = ($data['lang'] == 'en') ? 'title' : $data['lang'].'_title';

            $rules = [
                $title          => 'required',
                'slug'          => 'required|unique_post:'.$this->site_id,
                'regular_price' => 'required',
                'status'        => 'required',
            ];    

            Validator::extend('unique_post', function ($attribute, $value, $param, $validator) {
                $post = $this->post->where('post_name', $value)
                                   ->where('site_id', $param[0])
                                   ->exists();
                                   
                return $post ? false : true;
            });  

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
            $post->post_title   = $slug = Input::get('title') ?? Input::get($title);
            $post->post_name    = Input::get('slug') ? text_to_slug(Input::get('slug')) : text_to_slug($slug);

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

        $data['lang'] = $this->setting->get_setting('site_language');
        $data['currency'] = $this->setting->get_setting('currency');

        $data['memberships'] = $this->post->where('post_type', 'membership')
                                          ->where('post_status', 'actived')
                                          ->orderBy('post_order', 'ASC')
                                          ->get();
                                          
        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        $data['attributes'] = $this->post->site()
                                         ->where('post_type', 'product-attribute')
                                         ->get()
                                         ->pluck('post_title', 'id')
                                         ->toArray(); 

        $data['reviews'] = $this->post->site()
                                      ->where('post_type', 'comment')
                                      ->where('post_parent', $id)
                                      ->orderBy('id', 'DESC')
                                      ->get(); 

        if( Input::get('_token') )
        {
            $title = ($data['lang'] == 'en') ? 'title' : $data['lang'].'_title';

            $rules = [
                $title          => 'required',
                'slug'          => 'required|unique_post:'.$id.','.$this->site_id,
                'regular_price' => 'required',
                'status'        => 'required',
            ];    

            Validator::extend('unique_post', function ($attribute, $value, $param, $validator) {
                $post = $this->post->where('post_name', $value)
                                   ->where('id', '!=', $param[0])
                                   ->where('site_id', $param[1])
                                   ->exists();
                                   
                return $post ? false : true;
            });      

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }


            $inputs = Input::except(['_token', 'title', 'content', 'slug', 'status', 'lang', 'id']);

            unset($inputs['extra'][0]);

            $post = $this->post->find( $id );

            $post->site_id      = $this->site_id;
            $post->post_content = Input::get('content');                
            $post->post_title   = $slug = Input::get('title') ?? Input::get($title);
            $post->post_name    = Input::get('slug') ? text_to_slug(Input::get('slug')) : text_to_slug($slug);
            $post->post_type    = $this->post_type;
            $post->post_status  = Input::get('status');
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {
                $inputs['variation_attributes'] = array_cartesian_product( Input::get('attribute_data') );        
                                             
                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_val));
                }
                
                return Redirect::back()
                               ->with('success', trans('messages.updated', ['variable' => ucfirst($this->single)]));
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
    
    public function data_attributes()
    {   
        $data = array();
        $type = \Request::header('type');
        $data['currency'] = $this->setting->get_setting('currency');

        if( $type == 'add-attribute' ) {
            $attribute = Input::get('attribute');

            if( $attribute != 'custom') {
                $data['attributes'] = $this->post->site()
                                                 ->where('post_type', 'product-attribute')
                                                 ->get()
                                                 ->pluck('post_title', 'id')
                                                 ->toArray(); 
                
                $data['attribute_values'] = array();
                $data['attribute_id'] = $attribute;
            }

            return view($this->view.'.data.attributes', $data);
            
        } elseif( $type == 'save-attributes' ) {          
            $id = Input::get('id');                       

            $inputs['attribute_data'] = Input::get('attribute_data');
            $inputs['variation_attributes'] = array_cartesian_product( Input::get('attribute_data') );                                                 
    
            foreach ($inputs as $meta_key => $meta_val) {
                $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_val));
            }

        }

    }

    //--------------------------------------------------------------------------
    
    public function data_variations()
    {   
        $data = array();
        $type = \Request::header('type');
        $data['currency'] = $this->setting->get_setting('currency');

        if( $type == 'add-variations' ) {
            $variation = Input::get('variations');
            $id = Input::get('id');   
            $data['info'] = $info = $this->post->find( $id );
            foreach ($info->postmetas as $postmeta) {
                $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
            }

            return view($this->view.'.data.variations', $data);
            
        } elseif( $type == 'save-variations' ) {          
            $id = Input::get('id');                                            
            $attribute_data = Input::get('variations_data');
            $this->postmeta->update_meta($id, 'variations_data', array_to_json($attribute_data));
        }

    }

    //--------------------------------------------------------------------------

    public function import()
    {
        if( Input::hasFile('file') ) {
            $file = Input::file('file');

            $filename = strtolower($file->getClientOriginalName());
            $handle = fopen($file, "r");

            DB::connection()->disableQueryLog();

            $files = ['product'];

            if( ! str_contains( $filename, $files) ) {
                return Redirect::back()
                               ->with('error', 'Filename is not recognize.');
            }

            $i = $h = 0;
            while (($data = fgetcsv($handle)) !== FALSE) {

                if( $i == 0) {
                    $header = $data;                               
                } else {
                    $e=0;
                    foreach ($data as $d) {                     
                        if( @$header[$e] ) {                        
                            $col = str_replace([' ', '-'], ['_', ''], strtolower( rtrim( $header[$e] ) ) );
                            $order_info[$col] = $d;
                            $e++;
                        }
                    }
                    $rows[$i] = $order_info;    
                }

                $i++;
            }

            foreach ($rows as $row) {

                $product_name = @$row['product_name'];
                $slug = text_to_slug($product_name);

                $p = $this->post->site()->where('post_name', $slug)->first();

                if( ! $p ) {
                    $post = $this->post;
                    $post->site_id      = $this->site_id;
                    $post->post_author  = $this->user_id;
                    $post->post_content = '';                
                    $post->post_title   = $product_name;
                    $post->post_name    = $slug;
                    $post->post_type    = $this->post_type;
                    $post->post_status  = 'on-hold';

                    if( $post->save() ) {
                        unset($row['product_name']);
                        foreach ($row as $meta_key => $meta_val) {
                            $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
                        }
                    }    
                }             
            }

            return Redirect::back()
                           ->with('success', 'Products has been imported!');
        }                                        

        return Redirect::back()
                       ->with('error', 'Please select file to import!');    
    }

    //--------------------------------------------------------------------------

    public function export()
    {

        $date = date('Y-m-d H:i:s');
        // output headers so that the file is downloaded rather than displayed
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="products-'.$date.'.csv"');
         
        // do not cache the file
        header('Pragma: no-cache');
        header('Expires: 0');
         
        // create a file pointer connected to the output stream
        $file = fopen('php://output', 'w');


        // send the column headers
        $columns = [
            "PRODUCT NAME",
            "SKU",
            "REGULAR PRICE",
            "SALE PRICE",
            "STATUS"
        ];

        fputcsv($file, $columns);
   
        // output each row of the data
        $data_new=array();

        parse_str( query_vars(), $search );

        $rows = $this->post
                     ->site()
                     ->where('post_type', $this->post_type)
                     ->search($search)
                     ->get();

        foreach($rows as $row) {
            $postmeta = get_meta( $row->postMetas()->get() );

            $data_new = [
                "PRODUCT NAME"  => @$row->post_title,
                "SKU"           => @$postmeta->sku,
                "REGULAR PRICE" => @$postmeta->regular_price,
                "SALE PRICE"    => @$postmeta->sale_price,
                "STATUS"        => $row->post_status
            ];
            
            fputcsv($file, $data_new);
        }


    }
    
    //--------------------------------------------------------------------------

}

