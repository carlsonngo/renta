<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
  /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

	public $timestamps = false;
  
  //----------------------------------------------------------------  

  public function scopeSite($query) {
      $query->where('site_id', get_domain() );
      return $query;
  }

	//----------------------------------------------------------------	

	public static function get_setting($key, $site_id='') {

    $where['site_id'] = $site_id ? $site_id : get_domain();
    $where['key']     = $key;

		$data = Setting::where($where)->first();
    return $data ? $data->value : '';
	}

	//----------------------------------------------------------------

  public static function get_settings($site_id='') {

    $where['site_id'] = $site_id ? $site_id : get_domain();
    return (object)Setting::where($where)->get()->pluck('value', 'key')->toArray();
  }

  //----------------------------------------------------------------

  public static function insert_meta($key, $value) {
      $postmeta = new Setting();
      $postmeta->site_id = get_domain();
      $postmeta->key     = $key;
      $postmeta->value   = $value;
      $postmeta->save();
  }

	//----------------------------------------------------------------

  public static function update_meta($key, $value) {
      $postmeta = Setting::where('site_id', get_domain())->where('key', $key)->first();
      if($postmeta) {
	      $postmeta->site_id = get_domain();
          $postmeta->value   = $value;
          $postmeta->save();
      } else {
          Setting::insert_meta($key, $value);
      }
  }

	//----------------------------------------------------------------

  public function update_metas($inputs, $site_id = '') {
    $site_id = $site_id ? $site_id : get_domain();

    foreach($inputs as $key => $val) {

        $setting = Setting::where('site_id', $site_id)->where('key', $key)->first();
        if( ! $setting ) {
            $setting = new Setting();
        }

        $setting->site_id = $site_id;
        $setting->key     = $key;
        $setting->value   = array_to_json($val);
        $setting->save();                    
    }   
  }

  //----------------------------------------------------------------

}
