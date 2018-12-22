<?php
function getMonths($val ='') {
 
	$data = array(
		"01" => "January",
		"02" => "February",
		"03" => "March",
		"04" => "April",
		"05" => "May",
		"06" => "June",
		"07" => "July",
		"08" => "August",
		"09" => "September",
		"10" => "October",
		"11" => "November",
		"12" => "December"
	);

	return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function civil_status($val ='') {

  $data = array(
    'single'   => 'Single',
    'married'  => 'Married',
    'divorced' => 'Divorced',
    'widowed ' => 'Widowed ',
  );
  
  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function genders($val ='') {

	$data['male'] = trans('select.gender.male');
	$data['female'] = trans('select.gender.female');

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function countries($val ='') {
	$data = json_decode(file_get_contents('data/countries.json'), true);
	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function payment_method($val='') {
	$data = [
		'paypal' => 'Paypal', 
		'credit_card' => 'Direct payment with Credit Card'
	];

	return ($val) ? @$data[$val] : $data;
}

//----------------------------------------------------------------

function order_by($val ='') {

  $data = array(
    'DESC' => trans('select.order.desc'), 
    'ASC'  => trans('select.order.asc'),
  );

  return ($val) ? @$data[$val] : $data;
}

//----------------------------------------------------------------

function event_type($val ='') {

  $data = array(
    'reservation'  => 'Reservation', 
    'ticket' => 'Ticket Order',
  );

  return ($val) ? @$data[$val] : $data;
}

//----------------------------------------------------------------

function user_languages($val='') {
	$languages = json_decode(file_get_contents('data/languages.json'));

	foreach($languages as $lang) {
		$data[$lang->code] = $lang->name;
	}

	return ($val) ? @$data[$val] : $data;
}

//----------------------------------------------------------------

function get_times($val='') {

	$data = json_decode(file_get_contents('data/times.json'));

	return ($val) ? @$data[$val] : $data;
}

//----------------------------------------------------------------

function status_ico($val) {

	$data['published'] = '<span class="badge badge-primary text-uppercase p-2">'.trans('select.status.published').'</span>';
	$data['draft'] = '<span class="badge badge-warning text-uppercase p-2">'.trans('select.status.draft').'</span>';
	
	$data['actived'] = '<span class="badge badge-primary text-uppercase p-2">'.trans('select.status.actived').'</span>';
	$data['inactived'] = '<span class="badge badge-warning text-uppercase p-2">'.trans('select.status.inactived').'</span>';

	$data["shipping"] = '<span class="badge badge-warning text-uppercase sbold p-2">Shipping</span>';
	$data['shipped']  = '<span class="badge badge-primary text-uppercase sbold p-2">Shipped</span>';
	$data['on-hold']    = '<span class="badge badge-danger text-uppercase sbold p-2">On-Hold</span>';
	$data['pending']    = '<span class="badge badge-danger text-uppercase sbold p-2">Pending</span>';
	$data["confirmed"] = '<span class="badge badge-primary text-uppercase sbold p-2">Confirmed</span>';
	$data["reserved"] = '<span class="badge badge-warning text-uppercase sbold p-2">Reserved</span>';
	$data["processing"] = '<span class="badge badge-warning text-uppercase sbold p-2">Processing</span>';
	$data['completed']  = '<span class="badge badge-primary text-uppercase sbold p-2">Completed</span>';
	$data['paid']  = '<span class="badge badge-primary text-uppercase sbold p-2">Paid</span>';
	$data["cancelled"]  = '<span class="badge badge-default text-uppercase sbold p-2">Cancelled</span>';

	echo $data[$val];
}

//----------------------------------------------------------------

function user_group($val ='') {
 
 	$groups = App\Post::where('post_type', 'group')
				 	  ->where('post_status', 'actived')
				 	  ->get()
				 	  ->pluck('post_title', 'post_name')
				 	  ->toArray();

	$data = array(
		"admin" => "Admin",
		"customer" => "Customer",
	) + $groups;

	return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function payment_status($val ='') {
 
  $data = array(
    "paid"    => "Paid",
    "unpaid"  => "Unpaid",
    "partial" => "Partial",
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function post_status($val ='') {
 
  $data = array(
    "published" => trans('select.status.published'),
    "draft"     => trans('select.status.draft'),
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function active_status($val ='') {

  $data = array(
    'actived'   => trans('select.status.actived'),
    "inactived" => trans('select.status.inactived'),
  );
  
  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function languages($val ='') {

  $data = array();

  if( App\Setting::get_setting('localization') ) {
	  $data = site_languages($val);
  }
  
  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function site_languages($val ='') {

  $data['en'] = 'English';
  $data['de'] = 'German';
  $data['fr'] = 'French';	
  
  return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function themes($val ='') {
	$data['default'] = 'Default';
	$files =  glob(public_path() .'/css/themes/*');
	foreach ( $files as $file => $full_path ) {
	    $path = explode('/', $full_path);
	    $temp = str_replace(['.css'], '', end($path) );
	    if ( ! preg_match( '|Theme Name: (.*)$|mi', file_get_contents( $full_path ), $header ) ) {
	      continue;
	    }
		$data[$temp] = $header[1];
	}
	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function menus($val ='') {

	$data = array(
		'header' => trans('backend.header'),
		'footer' => trans('backend.footer'),
	);

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function media_formats($val ='') {

	$data = array(
		'audio'    => trans('select.media_format.audio'),
		'image'    => trans('select.media_format.image'),
		'video'    => trans('select.media_format.video'),
		'document' => trans('select.media_format.document'),
	);

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function mail_encryption($val ='') {
	$data = array(
		""     => "None", 
		"tls"  => "tls",
		"ssl"  => "ssl",
	);

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function media_sort($val ='') {

	$data = array(
		'date_desc' => trans('select.media_order.date_desc'),
		'date_asc'  => trans('select.media_order.date_asc'),
	);

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function shop_sort($val ='') {

	$data = array(
		'created_at-desc' => 'Date: Latest',
		'created_at-asc'  => 'Date: Oldest',
		'post_title-asc'  => 'Name: Ascending',
		'post_title-desc' => 'Name: Descending',
		'price-asc'       => 'Price: Low to High',
		'price-desc'      => 'Price: High to Low',
	);

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function get_file_format($type) {

	$val = @explode('/', $type);

	if( in_array($val[0], ['text', 'application']) || in_array($val[1], ['svg', 'svg+xml'])) {
		$data = 'document';
	} elseif( in_array($val[0], ['video']) ) {
		$data = 'video';
	} elseif( in_array($val[0], ['audio']) ) {
		$data = 'audio';
	} elseif( in_array($val[0], ['image']) ) {
		$data = 'image';
	} else {
		$data = 'default';		
	}

	return $data;
}

//----------------------------------------------------------------

function css_themes($val ='') {

	$theme = array(
		"default",
		"3024-day",
		"3024-night",
		"abcdef",
		"ambiance",
		"base16-dark",
		"base16-light",
		"bespin",
		"blackboard",
		"cobalt",
		"colorforth",
		"darcula",
		"dracula",
		"duotone-dark",
		"duotone-light",
		"eclipse",
		"elegant",
		"erlang-dark",
		"gruvbox-dark",
		"hopscotch",
		"icecoder",
		"idea",
		"isotope",
		"lesser-dark",
		"liquibyte",
		"lucario",
		"material",
		"mbo",
		"mdn-like",
		"midnight",
		"monokai",
		"neat",
		"neo",
		"night",
		"oceanic-next",
		"panda-syntax",
		"paraiso-dark",
		"paraiso-light",
		"pastel-on-dark",
		"railscasts",
		"rubyblue",
		"seti",
		"shadowfox",
		"solarized dark",
		"solarized light",
		"the-matrix",
		"tomorrow-night-bright",
		"tomorrow-night-eighties",
		"ttcn",
		"twilight",
		"vibrant-ink",
		"xq-dark",
		"xq-light",
		"yeti",
		"zenburn",
	);

	foreach ($theme as $key) {
		$data[$key] = $key;
	}

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function email_shortcodes($val ='') {

	$data = array(
		'standard-registration' => array(
			'[firstname]',
			'[lastname]',
			'[site_title]',
			'[email_address]',
			'[date_register]',
			'[confirm_url]',
			'[login_url]',
		),
		'premium-registration' => array(
			'[firstname]',
			'[lastname]',
			'[site_title]',
			'[email_address]',
			'[date_register]',
			'[login_url]',
		),
		'confirm-registration' => array(
			'[firstname]',
			'[lastname]',
			'[membership_type]',
			'[site_title]',
			'[email_address]',
			'[password]',
			'[login_url]',
		),
		'new_order' => array(
			'[site_title]',
			'[name]',
			'[order_number]',
			'[order_date]',
			'[order_details]',
			'[email_address]',
			'[phone]',
			'[total]',
			'[billing_address]',
			'[shipping_address]',
		),
		'on_hold_order' => array(
			'[site_title]',
			'[name]',
			'[order_number]',
			'[order_date]',
			'[order_details]',
			'[payment_details]',
			'[date_created]',
			'[total]',

		),
		'cancelled_order' => array(
			'[site_title]',
			'[name]',
			'[order_number]',
			'[order_date]',
			'[order_details]',
			'[date_created]',
			'[total]',
		),
		'processing_order' => array(
			'[site_title]',
			'[name]',
			'[order_number]',
			'[order_date]',
			'[order_details]',
			'[date_created]',
			'[total]',
		),
		'completed_order' => array(
			'[site_title]',
			'[name]',
			'[order_number]',
			'[order_date]',
			'[order_number]',
			'[order_details]',
			'[date_created]',
			'[total]',
		),
		'new-user' => array(
			'[site_title]',
			'[firstname]',
			'[lastname]',
			'[group]',
			'[email_address]',
			'[username]',
			'[password]',
			'[date_register]',
			'[login_url]',
		),
	);

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function attendees($val ='') {
 
  $data = array(
    "women"       => trans('backend.women'),
    "men" 		  => trans('backend.men'),
    "pairs"  	  => trans('backend.pairs'),
    "transgender" => trans('backend.transgender'),
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function order_status($val ='') {
 
  $data = array(
    "pending"    => "Pending",
    "processing" => "Processing",
    "completed"  => "Completed",
    "shipping"   => "Shipping",
    "shipped"    => "Shipped",
    "on-hold"    => "On-Hold",
    "cancelled"  => "Cancelled",
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function user_status($val ='') {
 
  $data = array(
    "inactived"    => "inactived",
    "actived"    => "actived",
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function booking_status($val ='', $type = '') {
 
  $data["pending"]    = "Pending";

  if($type == 'reservation') $data["reserved"] = "Reserved";

  if($type == 'ticket') $data["paid"]= "Paid";

  $data["cancelled"]  = "Cancelled";

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function shop_emails($val ='') {
 
  $data = array(
    "new_order"    => "New Order",
    "on_hold_order"  => "Order On-Hold",
    "cancelled_order" => "Cancelled Order",
    "processing_order"  => "Processing Order",
    "completed_order"  => "Completed Order",
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function payment_methods($val ='') {
 
  $data = array(
    "paymaya"  => array(
    	"name" => "PayMaya",
    	"description" => "Pay with your credit card via PayMaya Payments.",
    ),
    "paypal"   => array(
    	"name" => "PayPal",
    	"description" => "Pay via PayPal, you can pay with your credit card if you don't have PayPal account.",
    ),
    "bank_transfer" => array(
    	"name" => "Direct bank transfer",
    	"description" => "Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account." 
    )
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function product_types($val ='') {
 
  $data = array(
    "simple"   => "Simple Product",
    "variable" => "Variable Product",
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function discount_types($val ='') {
 
  $data = array(
    "fixed"   => trans('backend.fixed_discount'),
    "percent" => trans('backend.percentage_discount'),
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------

function discount_amount($amount ='', $type ='') {
	$cs = currency_symbol(App\Setting::get_setting('currency'));
	if($type == "fixed")  $amount = $cs.' '.$amount;
	if($type == "percent")  $amount = $amount.' %';
	return $amount;
} 

//----------------------------------------------------------------

function delivery_fee($val ='') {
 
  $data = array(
    "11:00-20:00" => [
    	"amount" => 0,
    	"desc" => "11:00 am - 8:00 pm"
    ],
    "21:00-10:00" => [
	    "amount" => 150,
    	"desc" => "9:00 pm - 10:00 am ( Special Delivery )"
],
  );

  return ($val) ? $data[$val] : $data;
} 

//----------------------------------------------------------------
