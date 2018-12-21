<?php
function is_plural($string) {
  return ($string > 1) ? 's' : '';
}

//----------------------------------------------------------------

function ordinal($num) {
  if (!in_array(($num % 100),array(11,12,13))){
    switch ($num % 10) {
      // Handle 1st, 2nd, 3rd
      case 1:  return $num.'st';
      case 2:  return $num.'nd';
      case 3:  return $num.'rd';
    }
  }
  return $num.'th';
}

//----------------------------------------------------------------

function get_c_months($val ='') {  
  foreach (range(date('m'), 12) as $month) {
    $m =sprintf('%02d', $month);
    $data[$m] = getMonths($m); 
  } 

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function get_cc_months($val ='') {  
  foreach (range(1, 12) as $month) {
    $m =sprintf('%02d', $month);
    $data[$m] = getMonths($m); 
  } 

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function get_cc_years($val ='') {  
  foreach (range(date('Y'), date('Y')+7) as $year) {
    $data[$year] = $year; 
  } 

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------


function code_to_text($val ='') {
  return ucwords(str_replace(['_','-'], [' ',' '], $val));  
}

//----------------------------------------------------------------

function label_tags($data ='') {
  $rows = explode(',', $data);
  foreach ($rows as $row) {
    echo '<a href="'.url('tags/'.$row).'" class="badge badge-info mr-1 mb-2 px-2 py-1">'.$row.'</a>';
  }
}

//----------------------------------------------------------------

function stars_review($count = 0) {
  $star = '';
  if($count) {
    $c = explode('.', $count);
    foreach(range(1, $c[0]) as $s) {
      $star .= '<i class="fa fa-star"></i> ';  
    }

    if(@$c[1]) $star .= '<i class="fa fa-star-half"></i> ';   
  }

  echo $star;
}

//----------------------------------------------------------------

function selected($val, $post) {
  return ($val == $post) ? 'selected=selected' : '';  
}

//----------------------------------------------------------------

function actived($val, $post) {
  return ($val == $post) ? 'active' : ''; 
}

//----------------------------------------------------------------

function checked($val, $post) {
  return ($val == $post) ? 'checked=checked' : '';  
}

//----------------------------------------------------------------

function checked_in_array($val, $data) {
  return @in_array($val, $data) ? 'checked=checked' : '';  
}



//----------------------------------------------------------------

function status($val ='') {
  return ($val == 1) ? 'Active' : 'Inactive'; 
}

//----------------------------------------------------------------

function time_zone($val='') {

  $timezone = (array)json_decode(file_get_contents('data/timezone.json'));

  if( $val ) {
      foreach($timezone as $s) {
        foreach($s as $k => $v) {
          $data[$k] = $v;
        }
      }
    return $data[$val];
  } else {
    foreach($timezone as $k => $v) {
      $data[$k] = (array)$v;
    }

    return $data;
  }
}

//----------------------------------------------------------------

function currencies($val='') {

  $timezone = (array)json_decode(file_get_contents('data/currencies.json'));

  foreach($timezone as $k => $v) {
    $data[$k] = $v->name;
  }

  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function currency_symbol( $val = '' ) {

  $timezone = (array)json_decode(file_get_contents('data/currencies.json'));

  foreach($timezone as $k => $v) {
    $data[$k] = $v->symbol_native;
  }

  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function amount_formatted($amount = '', $currency ='') {
  $currency = $currency ? $currency : @App\Setting::get_setting('currency'); 

  if( $currency && is_numeric($amount) ) {
    return currency_symbol($currency).' '.number_format($amount, 2);
  }
}

//----------------------------------------------------------------

function sort_array_multidim(array $array, $order_by) {
    //TODO -c flexibility -o tufanbarisyildirim : this error can be deleted if you want to sort as sql like "NULL LAST/FIRST" behavior.
    if(!is_array($array[0]))
        throw new Exception('$array must be a multidimensional array!',E_USER_ERROR);
    $columns = explode(',',$order_by);
    foreach ($columns as $col_dir)
    {
        if(preg_match('/(.*)([\s]+)(ASC|DESC)/is',$col_dir,$matches))
        {
            if(!array_key_exists(trim($matches[1]),$array[0]))
                trigger_error('Unknown Column <b>' . trim($matches[1]) . '</b>',E_USER_NOTICE);
            else
            {
                if(isset($sorts[trim($matches[1])]))
                    trigger_error('Redundand specified column name : <b>' . trim($matches[1] . '</b>'));
                $sorts[trim($matches[1])] = 'SORT_'.strtoupper(trim($matches[3]));
            }
        }
        else
        {
            throw new Exception("Incorrect syntax near : '{$col_dir}'",E_USER_ERROR);
        }
    }
    //TODO -c optimization -o tufanbarisyildirim : use array_* functions.
    $colarr = array();
    foreach ($sorts as $col => $order)
    {
        $colarr[$col] = array();
        foreach ($array as $k => $row)
        {
            $colarr[$col]['_'.$k] = strtolower($row[$col]);
        }
    }
   
    $multi_params = array();
    foreach ($sorts as $col => $order)
    {
        $multi_params[] = '$colarr[\'' . $col .'\']';
        $multi_params[] = $order;
    }
    $rum_params = implode(',',$multi_params);
    eval("array_multisort({$rum_params});");
    $sorted_array = array();
    foreach ($colarr as $col => $arr)
    {
        foreach ($arr as $k => $v)
        {
            $k = substr($k,1);
            if (!isset($sorted_array[$k]))
                $sorted_array[$k] = $array[$k];
            $sorted_array[$k][$col] = $array[$k][$col];
        }
    }
    return array_values($sorted_array);
}

//----------------------------------------------------------------

function has_photo($path ='') {
  if($path) {

    if( file_exists($path) ) {
      return asset($path);    
    }
  }
  // Default
  return asset('assets/uploads/avatar.png');
}

//----------------------------------------------------------------

function has_image($path ='') {
  if($path) {
    if( file_exists($path) ) {
      return asset($path);    
    }
  }
  $default = 'assets/img/no-image-found.jpg';
  return asset($default).'?v='.filemtime(public_path($default));
}

//----------------------------------------------------------------

function time_formatted($date ='') {  
  if( $date == '' || $date == '0000-00-00' || $date == '1970-01-01') return '';
  return date('H:i', strtotime($date));
}
//----------------------------------------------------------------

function date_formatted($date ='') {  
  if( $date == '' || $date == '0000-00-00' || $date == '1970-01-01') return '';
  if (preg_match("/\d{4}\-\d{2}-\d{2}/", $date)) {
      return date('d-M-Y', strtotime($date));
  } else {
      return date('Y-m-d', strtotime($date));
  }
}

//----------------------------------------------------------------

function date_formatted_b($date ='') {  

  if( $date == '' || $date == '0000-00-00' || $date == '1970-01-01') return '';

  if (preg_match("/\d{4}\-\d{2}-\d{2}/", $date)) {
      return date('m-d-Y', strtotime($date));
  } else {
      list($m, $d, $y) = explode('-', $date);
      return $y.'-'.$m.'-'.$d;
  }
}

//----------------------------------------------------------------

function name_formatted($user_id, $format = 'l, f') { 
  $d = App\User::find($user_id);

  $split_format = str_split($format);
  $name ='';
  foreach ($split_format as $char) {
    
    if (preg_match('/[a-zA-Z]/', $char)) {
      $n = ($char == 'l') ? 'lastname' : 'firstname';
      $name .= @$d->$n;
    } else {
      $name .= $char;
    }
  }
  
  return ucwords($name);
}

//----------------------------------------------------------------

function time_ago($time_ago) {

    if(!$time_ago) return false;

    $time_ago     = strtotime($time_ago);
    $cur_time     = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds      = $time_elapsed ;
    $minutes      = round($time_elapsed / 60 );
    $hours        = round($time_elapsed / 3600);
    $days         = round($time_elapsed / 86400 );
    $weeks        = round($time_elapsed / 604800);
    $months       = round($time_elapsed / 2600640 );
    $years        = round($time_elapsed / 31207680 );

    // Seconds
    if($seconds <= 60){
        return trans('select.time_ago.just_now');
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "1 ".trans('select.time_ago.minute_ago');
        }
        else{
            return "$minutes ".trans('select.time_ago.minutes_ago');
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return trans('select.time_ago.hour_ago');
        }else{
            return "$hours ".trans('select.time_ago.hours_ago');
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return trans('select.time_ago.yesterday');
        }else{
            return "$days ".trans('select.time_ago.days_ago');
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return trans('select.time_ago.week_ago');
        }else{
            return "$weeks ".trans('select.time_ago.weeks_ago');
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return trans('select.time_ago.month_ago');
        }else{
            return "$months ".trans('select.time_ago.months_ago');
        }
    }
    //Years
    else{
        if($years==1){
            return trans('select.time_ago.year_ago');
        }else{
            return "$years ".trans('select.time_ago.years_ago');
        }
    }
}

//----------------------------------------------------------------

function array_to_json($val='') {

  if( is_array($val) ) {
    $val = json_encode($val);
  }

  return $val;
}

//----------------------------------------------------------------

function text_to_slug($val='') {
  return str_replace([' ', '(',')', "'"], ['-','',''], strtolower($val));
}

//----------------------------------------------------------------

function query_vars($query ='') {

  $qs = $_SERVER['QUERY_STRING'];
  $vars = array();

  if($query == '') return $qs;

    parse_str($_SERVER['QUERY_STRING'], $qs);
    
    foreach ($qs as $key => $value) {     
      $vars[$key] = $value;

      if($value == '0') {
        unset($vars[$key]);   
      }
    }
 
    parse_str($query, $queries);

    foreach ($queries as $key => $value) {      
      $vars[$key] = $value;

      if($value == '0') {
        unset($vars[$key]);   
      }
    }

    return $vars;
}

//----------------------------------------------------------------

function get_meta($rows = array()) {
  $data = array();
  foreach($rows as $row) { 
    $data[$row->meta_key] = $row->meta_value;
  }

  return (object)$data;
}

//----------------------------------------------------------------

function str_mask( $str, $start = 0, $length = null ) {
    $mask = preg_replace ( "/\S/", "*", $str );
    if( is_null ( $length )) {
        $mask = substr ( $mask, $start );
        $str = substr_replace ( $str, $mask, $start );
    }else{
        $mask = substr ( $mask, $start, $length );
        $str = substr_replace ( $str, $mask, $start, $length );
    }
    return $str;
}

//----------------------------------------------------------------

function compress($source, $destination, $quality) {

  $info = getimagesize($source);

  if ($info['mime'] == 'image/jpeg') 
    $image = imagecreatefromjpeg($source);

  elseif ($info['mime'] == 'image/gif') 
    $image = imagecreatefromgif($source);

  elseif ($info['mime'] == 'image/png') 
    $image = imagecreatefrompng($source);

  imagejpeg($image, $destination, $quality);

  return $destination;
}

//----------------------------------------------------------------

function cURL($url, $header=NULL, $cookie=NULL, $p=NULL) {
    $ch = curl_init();
    $ipku = $_SERVER['REMOTE_ADDR'];
    $ip = array("REMOTE_ADDR: $ipku", "HTTP_X_FORWARDED_FOR: $ipku");

    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_NOBODY, $header);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($ch, CURLOPT_COOKIE, $cookie);

    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    curl_setopt($ch,CURLOPT_HTTPHEADER,$ip);

    /*
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR,$cookie);
    curl_setopt($ch, CURLOPT_COOKIEFILE,$cookie);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT,'Opera/9.80 (Series 60; Opera Mini/6.5.27309/34.1445; U; en) Presto/2.8.119 Version/11.10');
    */

    if ($p) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
    }
    $result = curl_exec($ch);

    curl_close($ch);

    return $result;

}

//----------------------------------------------------------------

function upload_image($file, $path='', $old_file ='', $quality = 'compress', $set_width = '', $height ='') {
    $compress      = false;
    $filename   = str_random(16);
    $imageFile  = $file->getRealPath();
    $ext        = $file->getClientOriginalExtension();

    if( ! $set_width ) {
      $width = App\Setting::get_setting('img_width');
      if( !$width ) $width = 230;
    } 
    
    if( $quality == 'compress' ) {
      $compress      =  App\Setting::get_setting('img_compress');
      $compress_rate = App\Setting::get_setting('img_compress_rate');
      $compress_rate = $compress_rate ? $compress_rate : 100;
    }

    if( ! file_exists($path) ) mkdir($path, 0755,true);

    $profile_pic = $path.'/'.$filename.'.png';  
    if( file_exists($old_file) ) unlink($old_file);

    $img = \Image::make($imageFile)->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    })->save($profile_pic);
    if($compress) {
          compress($profile_pic, $profile_pic, $compress_rate);
    }
    return $profile_pic;
}

//----------------------------------------------------------------

function get_days_diff($date_start ='', $date_end ='') {
  $date_start = date_formatted_b($date_start);
  $date_end = date_formatted_b($date_end);
  $days = (strtotime($date_end) - strtotime($date_start)) / (60 * 60 * 24);
  return $days == 0 ? 1 : $days;
}

//----------------------------------------------------------------

function get_past_months($month) {
  foreach (range(1, $month) as $m) {
    $month = date('M', strtotime(date('Y-'.$m.'-d')));

      $data[$m] = $month;
  }
  return $data;
}

//----------------------------------------------------------------

function link_ordered_menu($array, $parent_id = 0, $actived='') {
    $menu_html = '<ul class="ordered-menu">';
    foreach($array as $element) {
        if($element['parent_id']==$parent_id) {
            $checked = @in_array($element['id'], $data) ? 'checked' : '';
            $menu_html .= '<li><a href="'.$element['href'].'"  class="'. actived($actived, $element['slug']) .'">'.$element['name'];
            $menu_html .= link_ordered_menu($array, $element['id'], $actived);
            $menu_html .= '</a></li>';
        }
    }
    $menu_html .= '</ul>';
    return $menu_html;
}

//----------------------------------------------------------------

function checkbox_ordered_menu($array, $parent_id = 0, $data) {

    $menu_html = '<ul class="ordered-menu list-unstyled">';
    foreach($array as $element) {
        if($element['parent_id']==$parent_id) {
            $checked = @in_array($element['id'], $data) ? 'checked' : '';
            $menu_html .= '<li class="list-unstyled"><label class="mt-checkbox mt-checkbox-outline">';
            $menu_html .= '<input type="checkbox" value="'.$element['id'].'" name="category[]" '.$checked.'>';
            $menu_html .= '<span></span> '.$element['name'];
            $menu_html .= '</label>';
            $menu_html .= checkbox_ordered_menu($array,$element['id'], $data);
            $menu_html .= '</li>';
        }
    }
    $menu_html .= '</ul>';
    return $menu_html;
}

//----------------------------------------------------------------

function radio_ordered_menu($array, $parent_id = 0, $id) {
    $menu_html = '<ul class="ordered-menu">';
    foreach($array as $element) {
        if($element['parent_id']==$parent_id) {
            $checked = $element['id'] == $id ? 'checked' : '';
            $menu_html .= '<li class="list-unstyled"><label class="mt-radio mt-radio-outline">';
            $menu_html .= '<input type="radio" value="'.$element['id'].'" name="category" '.$checked.'>';
            $menu_html .= '<span></span> '.$element['name'];
            $menu_html .= '</label>';
            $menu_html .= radio_ordered_menu($array,$element['id'], $id);
            $menu_html .= '</li>';
        }
    }
    $menu_html .= '</ul>';
    return $menu_html;
}

//----------------------------------------------------------------

function dropdown_ordered_menu($array, $parent_id = 0, $depth=0) {
    $menu_html = '';
    $nbsp = str_repeat('&nbsp;', $depth * 1);
    foreach($array as $element) {
        if($element['parent_id']==$parent_id) {
            $menu_html .= '<option value="'.$element['id'].'">'.$nbsp.$element['name'].'</option>';
            $menu_html .= dropdown_ordered_menu($array,$element['id'], $depth);
        } else {
          $depth++;
        }
    }
    return $menu_html;
}

//----------------------------------------------------------------

function _cleanup_header_comment( $str ) {
  return trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $str));
}

//----------------------------------------------------------------

function theme_templates($view = 'frontend/templates') {
  $templates['default'] = 'Default';

  $server = request()->server->get('SERVER_NAME');
  if( $server == 'localhost' ) {
    $find = [$_SERVER['HTTP_HOST'], '/public', '/index.php'];
    $dir = str_replace($find, '', $_SERVER['SCRIPT_FILENAME']);
  } else {
    $dir = str_replace('/public', '', realpath('.'));
  }


  $files =  glob($dir.'/resources/views/'.$view.'/*');
  foreach ( $files as $file => $full_path ) {
    $path = explode('/', $full_path);
    $temp = str_replace(['.blade', '.php'], '', end($path) );

    if ( ! preg_match( '|Template Name: (.*)$|mi', @file_get_contents( $full_path ), $header ) ) {
      continue;
    }
    $templates[$temp] = $header[1];
  }
  return $templates;
}

//----------------------------------------------------------------

function get_cc_type($str, $format = 'string') {
    if (empty($str)) {
        return false;
    }
    $matchingPatterns = [
        'visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
        'mastercard' => '/^5[1-5][0-9]{14}$/',
        'amex' => '/^3[47][0-9]{13}$/',
        'diners' => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
        'discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
        'jcb' => '/^(?:2131|1800|35\d{3})\d{11}$/',
        'any' => '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/'
    ];

    $ctr = 1;
    foreach ($matchingPatterns as $key=>$pattern) {
        if (preg_match($pattern, $str)) {
            return $format == 'string' ? $key : $ctr;
        }
        $ctr++;
    }
}

//----------------------------------------------------------------

function dirToArray($dir) { 
   $result = array(); 
   $cdir = scandir($dir); 
   foreach ($cdir as $key => $value) 
   { 
      if (!in_array($value,array(".",".."))) 
      { 
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
         { 
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
         } 
         else 
         { 
            $result[] = $value; 
         } 
      } 
   } 
   return $result; 
}

//----------------------------------------------------------------

function remove_directory($path) {
    // The preg_replace is necessary in order to traverse certain types of folder paths (such as /dir/[[dir2]]/dir3.abc#/)
    // The {,.}* with GLOB_BRACE is necessary to pull all hidden files (have to remove or get "Directory not empty" errors)
    $files = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path).'/{,.}*', GLOB_BRACE);
    foreach ($files as $file) {
      if ($file == $path.'/.' || $file == $path.'/..') { continue; } // skip special dir entries
      is_dir($file) ? remove_directory($file) : unlink($file);
    }
    rmdir($path);
    return;
}

//----------------------------------------------------------------

function human_filesize($size, $precision = 2) {
    for($i = 0; ($size / 1024) > 0.9; $i++, $size /= 1024) {}
    return round($size, $precision).' '.['B','kB','MB','GB','TB','PB','EB','ZB','YB'][$i];
}

//----------------------------------------------------------------

function get_mime_type($filename) {
    $idx = explode( '.', $filename );
    $count_explode = count($idx);
    $idx = strtolower($idx[$count_explode-1]);

    $mimet = array( 
        'txt' => 'text/plain',
        'htm' => 'code/html',
        'html' => 'code/html',
        'php' => 'code/html',
        'css' => 'code/css',
        'js' => 'code/javascript',
        'json' => 'code/json',
        'xml' => 'code/xml',
        'swf' => 'video/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'document/tiff',
        'tif' => 'document/tiff',
        'svg' => 'document/svg+xml',
        'svgz' => 'document/svg+xml',

        // archives
        'zip' => 'archive/zip',
        'rar' => 'archive/x-rar-compressed',
        'exe' => 'archive/x-msdownload',
        'msi' => 'archive/x-msdownload',
        'cab' => 'archive/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mp3',
        '3gp' => 'video/3gp',
        'mov' => 'video/mov',
        'qt'  => 'video/qt',
        'mov' => 'video/mov',

        // adobe
        'pdf' => 'document/pdf',
        'psd' => 'default/vnd.adobe.photoshop',
        'ai' => 'default/postscript',
        'eps' => 'default/postscript',
        'ps' => 'default/postscript',

        // ms office
        'doc' => 'document/msword',
        'rtf' => 'document/rtf',
        'xls' => 'spreadsheet/vnd.ms-excel',
        'ppt' => 'interactive/vnd.ms-powerpoint',
        'docx' => 'document/msword',
        'xlsx' => 'spreadsheet/vnd.ms-excel',
        'pptx' => 'interactive/vnd.ms-powerpoint',

        // open office
        'odt' => 'document/vnd.oasis.opendocument.text',
        'ods' => 'spreadsheet/vnd.oasis.opendocument.spreadsheet',
    );

    if (isset( $mimet[$idx] )) {
      return $mimet[$idx];
    } else {
      return 'default/octet-stream';
    }
}

//----------------------------------------------------------------

function media_library($dir='') {
  $contents = array();
  
  $format = Input::get('format');
  $sort   = Input::get('sort')=='date_asc' ? SORT_ASC : SORT_DESC;
        
  if( $format == 'image' ) {
      $glob[] = glob($dir."image/*-thumb*");
  } elseif( in_array($format, ['audio', 'video', 'document']) ) {
      $glob[] = glob($dir.$format."/*");              
  } else {
      $glob[] = glob($dir."image/*-thumb*");
      $glob[] = glob($dir."document/*");
      $glob[] = glob($dir."video/*");
      $glob[] = glob($dir."audio/*");            
  }      

  $data['files'] = $files = array_collapse($glob);

  array_multisort(array_map('filectime', $files), SORT_NUMERIC, $sort, $files);
  foreach ($files as $file) {
    $f    = explode("/", $file);
    $name = end($f);
    $id   = @explode("-", $name)[1];
    $ext  = pathinfo($file, PATHINFO_EXTENSION);

    $medium = str_replace('thumb', 'large', $file);
    if( file_exists(str_replace('thumb', 'medium', $file)) ) {
      $medium = str_replace('thumb', 'medium', $file);
    }
    
    $find = ['_', $id, '-thumb', '-medium', '-large', '-document', '-audio', '-video', '-'];

    if( $ext ) {
            $replace_name = str_replace($find, ' ', strstr($name,'.',true) );
            $name = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $replace_name)));        
    } else {
            $name = str_replace($find, ' ', $name);       
    }

    $format = @explode('/', get_mime_type($file))[0];

    $size = $format=='image' ? getimagesize($file) : '';

    $contents[] = array( 
      'file'      => $format=='image' ? $file : 'assets/img/media/'.$format.'.png',   
      'large'     => $format=='image' ? str_replace('-thumb', '-large', $file) : 'assets/img/media/'.$format.'.png',
      'medium'    => $format=='image' ? str_replace('-thumb', '-medium', $file) : 'assets/img/media/'.$format.'.png',
      'original'  => str_replace('-thumb', '-large', $file),
      'folder'    => $f[2],
      'id'        => $id,
      'name'      => $name, 
      'format'    => $format,
      'type'      => get_mime_type($file), 
      'size'      => human_filesize(filesize($file)),
      'dimension' => $format=='image' ? $size[0].' x '.$size[1] : '',
      'date'      => date('F d, Y', filectime($file))
    );
  
  }

  return $contents;  

}

//----------------------------------------------------------------

function trans_post($info = array(), $default ='', $trans ='') {

  $lang = Input::get('lang', App\Setting::get_setting('site_language'));

  if( $info ) {
    foreach ($info->postmetas as $postmeta) {
        $info->{$postmeta->meta_key} = $postmeta->meta_value;
    }    
  }

  return $lang=='en' ? @$info->{$default} : @$info->{$lang.$trans};
}

//----------------------------------------------------------------

function theme_style() {
    $ct = App\Setting::get_setting('current_theme');
    
    $min = 'css/themes/'.$ct.'.min.css';

    $themes[] = 'css/frontend.css';
    $themes[] = 'css/shop.css';

    if( file_exists($min ) ) {
      $themes[] = $min;  
    } else {
      $themes[] = 'css/themes/'.$ct.'.css';  
    }

    foreach($themes as $theme) {
      $theme_asset = asset($theme); 
      $public_path = public_path($theme);
      if( file_exists($public_path) ) {
        echo '<link rel="stylesheet" href="'.$theme_asset.'?'.date('YmdHis', filemtime($public_path)).'">';
        echo "\n";        
      } 
    }
}

//----------------------------------------------------------------

function regsiter_domain($data=array()) {
 
  $id          = (int)$data['id'];  
  $domain      = $data['name'] ? $data['name'] : \Request::getHost();
  $data_path   = app_path('helpers/domains.php');
  $domains     = include($data_path);

  $domains     = array_flip($domains);
  $find        = @$domains[$data['id']];

  $domains[$id] = $domain;
  $domains      = array_flip($domains);
  file_put_contents($data_path, '<?php return '.var_export($domains, true).';' );    

}

//----------------------------------------------------------------

function get_domain() {

  $domain      = \Request::getHost();
  $data_path   = app_path('helpers/domains.php');
  $domains     = include($data_path);

  return array_get($domains, $domain);
}

//----------------------------------------------------------------

function init_settings() {
      
  if( Schema::hasTable('settings') ) {

    $setting = (array)App\Setting::get_settings();

    // Auto domain register config
    if( ! $setting ) {
      App\Http\Controllers\DomainController::auto_register();
      return;
    }

    // Paypal config
    $paypal_conf = config('paypal');
    $paypal = json_decode(@$setting['paypal']);
    $paypal_conf['settings']['mode'] = @$paypal->status;
    if( @$paypal->status == 'sandbox' ) {
      $paypal_conf['secret']    = @$paypal->sandbox->secret;
      $paypal_conf['client_id'] = @$paypal->sandbox->client_id;
    } 
    if( @$paypal->status == 'live' ) {
      $paypal_conf['secret']    = @$paypal->live->secret;
      $paypal_conf['client_id'] = @$paypal->live->client_id;
    } 
    config()->set('paypal', $paypal_conf);

    // Maintenance Config
    if( @$setting['maintenance_mode'] && !in_array(Request::segment(1), ['b', 'auth']) ) {
      return abort('503');
    }

    $debug = @$setting['debug_mode'] ? true : false;
    config()->set('app.debug', $debug);

    // SMTP Mail config
    $lang = Input::get('lang', $setting['site_language']);

    $mail = config('mail');
    $mail["host"]       = @$setting['mail_host'];
    $mail["port"]       = @$setting['mail_port'];
    $mail["encryption"] = @$setting['mail_encryption'];
    $mail["username"]   = @$setting['mail_username'];
    $mail["password"]   = @$setting['mail_password'];

    config()->set('mail', $mail);

    App::setLocale($lang);

  } 

}

//----------------------------------------------------------------

function minified_css($path ='', $file ='') {

  $css = file_get_contents( public_path($path.$file).'.css' );
  $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css); // negative look ahead
  $css = preg_replace('/\s{2,}/', ' ', $css);
  $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
  $css = preg_replace('/;}/', '}', $css);

  file_put_contents(public_path($path.$file.'.min.css'), $css);
}

//----------------------------------------------------------------

function discount_percentage($sale_price =0, $regular_price =0) {

  $discount = '';
  if( $sale_price && $regular_price) {
    $discount = number_format((1 - ($sale_price / $regular_price)) * 100) .'%';
  }

  return $discount;
}

//----------------------------------------------------------------

function site_modules() {

  $data = array(
    array(
      'name' => 'google_translate',
      'label' => 'Google Translate <span class="text-muted small">/ Default translator will disable</span>'
    ),
    array(
      'name' => 'events_module',
      'label' => 'Events',
      'target' => 'events'
    ),
    array(
      'name' => 'bookings_module',
      'label' => 'Bookings <span class="text-muted small">/ Events Module must enabled</span>',
      'target' => 'bookings'
    ),
    array(
      'name' => 'gallery_module',
      'label' => 'Galleries',
      'target' => 'galleries'
    ),
    array(
      'name' => 'shop_module',
      'label' => 'Shop',
      'target' => 'shop'
    ),
    array(
      'name' => 'localization',
      'label' => 'Localization <span class="text-muted small">/ Language Settings</span>',
      'target' => 'site-lang',
    ),
    array(
      'name' => 'error_reports',
      'label' => 'Error Reports',
      'target' => 'error-reports',
    ),
    array(
      'name' => 'fsk18',
      'label' => 'FSK18 <span class="text-muted small">/ Private content</span>',
    ),
  );

  return $data;
}

//----------------------------------------------------------------

function array_cartesian_product($attrs = '') {

    if( ! $attrs ) return false;

    $arrays = array();
    foreach($attrs as $attr_k => $attribute_data) {
        if( @$attribute_data['is_variation']) {
            if( $attribute_data['is_term']) {
                foreach ($attribute_data['values'] as $val) {
                    $arrays[$attr_k][] = $val;
                }        
            } else {
                foreach (explode('|', $attribute_data['values']) as $val) {
                    $arrays[$attr_k][] = text_to_slug($val);
                }                    
            } 
        }
    }

    asort($arrays);

    $result = array();
    $arrays = array_values($arrays);
    $sizeIn = sizeof($arrays);
    $size = $sizeIn > 0 ? 1 : 0;
    foreach ($arrays as $array)
        $size = $size * sizeof($array);
    for ($i = 0; $i < $size; $i ++)
    {
        $result[$i] = array();
        for ($j = 0; $j < $sizeIn; $j ++)
            array_push($result[$i], current($arrays[$j]));
        for ($j = ($sizeIn -1); $j >= 0; $j --)
        {
            if (next($arrays[$j]))
                break;
            elseif (isset ($arrays[$j]))
                reset($arrays[$j]);
        }
    }

    return $result;
}

//----------------------------------------------------------------

function permalink($menu) {
  $link = $menu->value;
  if( $menu->type == 'product' ) {
    $link = 'shop/'.$menu->value;
  } if( $menu->type == 'event' ) {
    $link = 'events/'.$menu->value;
  }

  return asset($link);
}

//----------------------------------------------------------------

function is_bookable($info='') {

  $date = $info->date_start.' '.$info->time_start;

  if( strtotime(date('Y-m-d H:i:s')) > strtotime($date)  ) return false;
  if( @$info->allow_booking ) return true;

  return false;
}

//----------------------------------------------------------------

function is_past_date($date='') {
  if( str_replace(' ', '', $date) == '00:00' ) return false;
  if( strtotime(date('Y-m-d H:i:s')) > strtotime($date)  ) return true;

  return false;
}

//----------------------------------------------------------------

function check_postmeta($type='', $key ='', $value ='') {

  if( $type == 'event' && in_array($key, ['date_start', 'time_start'] )) {
    return '';
  }
  return $value;
}

//----------------------------------------------------------------

function has_discount($info) {

  if( ! $info->sale_price ) return false;

  $start_date = strtotime(date_formatted_b($info->sale_date_start).' '.$info->sale_time_start);
  $end_date   = strtotime(date_formatted_b($info->sale_date_end).' '.$info->sale_time_end);
  $today      = strtotime(date('Y-m-d H:i:s'));

  return ( ( $today >= $start_date ) && ( $today <= $end_date ) );
}

//----------------------------------------------------------------

function date_validity($date_start = '', $date_end = '') {

  $start_date = strtotime($date_start);
  $end_date   = strtotime($date_end);
  $today      = strtotime(date('Y-m-d H:i:s'));

  if( strlen($date_start) <= 6 || strlen($end_date) <= 6 ) return true;

  return ( ( $today >= $start_date ) && ( $today <= $end_date ) );
}

//----------------------------------------------------------------

function has_access($module ='', $roles = array()) {
  return App\Permission::has_access($module, $roles);
}

//----------------------------------------------------------------

function form_field($key ='', $value = '') { 

  if( in_array($key, ['last_login']) ) {
    $data = time_ago($value);
  } elseif( str_contains($key, ['country']) ) {
    $data = Form::select($key, countries(), $value, ['class' => 'form-control select2']);
  } else {
    $data = '<input name="'.$key.'" class="form-control" value="'.$value.'">';
  }

  return $data;
}

//----------------------------------------------------------------

function is_fsk18($meta, $val = array()) { 
  if( @$meta->fsk18 ) {
    $fsk18 = array_filter(array_values(json_decode($meta->fsk18, true)));
    if(!$val && $fsk18 ) return true;
    if( in_array($fsk18, $val) ) return true;    
  }
}

//----------------------------------------------------------------

function dates_from_range($first, $last, $format = 'Y-m-d', $step = '+1 day')
{
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {

        $dates[] = date($format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}

//----------------------------------------------------------------

function get_membership()
{
  if( Auth::check() ) {
    $user_id = Auth::User()->id;
    return App\UserMeta::get_meta($user_id, 'membership');
  }

  return 'guest';
}

//----------------------------------------------------------------

