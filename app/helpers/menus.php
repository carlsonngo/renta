<?php
function backend_menus() {
	$b = 'backend';
	$f = 'frontend';

	$menus['dashboard'] = array(
		'url'    => route($b.'.general.dashboard'),
		'name'   => trans('backend.dashboard'),
		'icon'   => 'fas fa-home',
		'child'  => array()
	);		

	$menus['users'] = array(
		'url'   => '',
		'name'  => trans('backend.users'),
		'icon'  => 'fas fa-users',
		'child' => array(
			array(
				'role' => [],
				'url'  => route($b.'.users.index'),
				'name' => trans('backend.all_users'),
			),
			array(
				'role' => ['add_edit'],
				'url'  => route($b.'.users.add'),
				'name' => trans('backend.add_new'),
			),
		)
	);

	$menus['groups'] = array(
		'url'   => '',
		'name'  => 'Groups',
		'icon'  => 'fas fa-users',
		'child' => array(
			array(
				'role' => [],
				'url'  => route($b.'.groups.index'),
				'name' => trans('backend.all_groups'),
			),
			array(
				'role' => ['add_edit'],
				'url'  => route($b.'.groups.add'),
				'name' => trans('backend.add_new'),
			),
		)
	);

	$menus['media'] = array(
		'url'   => '',
		'name'  => trans('backend.media'),
		'icon'  => 'fas fa-images',
		'child' => array(
			array(
				'role' => [],
				'url'  => route($b.'.media.index'),
				'name' => trans('backend.library'),
			),
			array(
				'role' => ['add'],
				'url'  => route($b.'.media.add'),
				'name' => trans('backend.add_new'),
			),
		)
	);

	$menus['pages'] = array(
		'url'   => '',
		'name'  => trans('backend.pages'),
		'icon'  => 'fas fa-copy',
		'child' => array(
			array(
				'role' => [],
				'url'  => route($b.'.posts.index', ['post_type' => 'page']),
				'name' => trans('backend.all_pages'),
			),
			array(
				'role' => ['add_edit'],
				'url'  => route($b.'.posts.add', ['post_type' => 'page']),
				'name' => trans('backend.add_new'),
			),
		)
	);

	$menus['posts'] = array(
		'url'   => '',
		'name'  => trans('backend.posts'),
		'icon'  => 'fas fa-thumbtack',
		'child' => array(
			array(
				'role' => [],
				'url'  => route($b.'.posts.index', ['post_type' => 'post']),
				'name' => trans('backend.all_posts'),
			),
			array(
				'role' => ['add_edit'],
				'url'  => route($b.'.posts.add', ['post_type' => 'post']),
				'name' => trans('backend.add_new'),
			),
			array(
				'role' => ['manage_category'],
				'url'  => route($b.'.categories.index', ['post_type' => 'post-category']),
				'name' => trans('backend.categories'),
			),
		)
	);

	if( App\Setting::get_setting('events_module') ) {
		$menus['events'] = array(
			'url'   => '',
			'name'  => trans('backend.events'),
			'icon'  => 'fas fa-calendar-alt',
			'child' => array(
				array(
					'role' => [],
					'url'  => route($b.'.posts.index', ['post_type' => 'event']),
					'name' => trans('backend.all_events'),
				),
				array(
					'role' => ['add_edit'],
					'url'  => route($b.'.posts.add', ['post_type' => 'event']),
					'name' => trans('backend.add_new'),
				),
				array(
					'role' => ['manage_category'],
					'url'  => route($b.'.categories.index', ['post_type' => 'event-category']),
					'name' => trans('backend.categories'),
				),
			)
		);
	}

	if( App\Setting::get_setting('bookings_module') && App\Setting::get_setting('events_module') ) {

	    $reservations = App\Post::site()
		                           ->where('post_type', 'reservation')
		                           ->whereIn('post_status', ['pending', 'confirmed'])
		                           ->count();

	    $tickets = App\Post::site()
	                          ->where('post_type', 'ticket')
	                          ->whereIn('post_status', ['pending', 'confirmed'])
	                          ->count();

		$menus['bookings'] = array(
			'url'   => '',
			'name'  => 'Bookings',
			'icon'  => 'fas fa-calendar-check',
			'child' => array(
				array(
					'role' => [],
					'url'  => route($b.'.bookings.index'),
					'name' => 'All Bookings',
				),
				array(
					'role' => ['calendar'],
					'url'  => route($b.'.bookings.calendar'),
					'name' => 'Calendar',
				),
				array(
					'role' => [],
					'url'  => route($b.'.bookings.index', ['post_type' => 'reservation']),
					'name' => 'Reservation',
					'count' => $reservations
				),
				array(
					'role' => [],
					'url'  => route($b.'.bookings.index', ['post_type' => 'ticket']),
					'name' => 'Ticket Orders',
					'count' => $tickets
				),
				array(
					'role' => ['report'],
					'url'  => route($b.'.bookings.report'),
					'name' => 'Report',
				),
			)
		);                          		
	}

	if( App\Setting::get_setting('gallery_module') ) {
		$menus['galleries'] = array(
			'url'   => '',
			'name'  => trans('backend.galleries'),
			'icon'  => 'fas fa-images',
			'child' => array(
				array(
					'role' => [],
					'url'  => route($b.'.galleries.index'),
					'name' => trans('backend.all_galleries'),
				),
				array(
					'role' => ['add_edit'],
					'url'  => route($b.'.galleries.add'),
					'name' => trans('backend.add_new'),
				),
			)
		);
	}

	$menus['domains'] = array(
		'url'   => '',
		'name'  => trans('backend.domains'),
		'icon'  => 'fas fa-sitemap',
		'child' => array(
			array(
				'role' => [],
				'url'  => route($b.'.domains.index'),
				'name' => trans('backend.all_domains'),
			),
			array(
				'role' => ['add_edit'],
				'url'  => route($b.'.domains.add'),
				'name' => trans('backend.add_new'),
			),
		)
	);

	$menus['memberships'] = array(
		'url'   => '',
		'name'  => 'Memberships',
		'icon'  => 'fas fa-users',
		'child' => array(
			array(
				'role' => [],
				'url'  => route($b.'.memberships.index'),
				'name' => 'All Memberships',
			),
			array(
				'role' => ['add_edit'],
				'url'  => route($b.'.memberships.add'),
				'name' => 'Add Membership',
			),
		)
	);

	if( App\Setting::get_setting('shop_module') ) {
		$menus['shop'] = shop_backend_menus();
		$menus['shop']['child'] = [];
		foreach( shop_backend_menus()['child'] as $shop_menu ) {
			if( has_access($shop_menu['module'], @$shop_menu['role']) ) {			
				$menus['shop']['child'][] = $shop_menu;
			}
		}
	}

	$menus['settings'] = array(
		'url'   => '',
		'name'  => trans('backend.settings'),
		'icon'  => 'fas fa-cogs',
		'child' => array(
			array(
				'role' => ['general'],
				'url'  => route($b.'.settings.general'),
				'name' => trans('backend.general'),
			),
			array(
				'role' => ['menu'],
				'url'  => route($b.'.settings.menus'),
				'name' => trans('backend.menu'),
			),
			array(
				'role' => ['slider'],
				'url'  => route($b.'.settings.slider'),
				'name' => trans('backend.slider'),
			),
			array(
				'role' => ['theme_style'],
				'url'  => route($b.'.settings.css-theme'),
				'name' => trans('backend.css_theme'),
			),
			array(
				'role' => ['email_template'],
				'url'  => route($b.'.settings.emails', ['email' => 'premium-registration']),
				'name' => trans('backend.email_template'),
			),
		)
	);


	if( App\Setting::get_setting('localization') ) {
		$menus['settings']['child'][] = array(
			'role' => ['localization'],
			'url'  => route($b.'.settings.localization'),
			'name' => trans('backend.localization'),
		);
	}

	if( App\Setting::get_setting('error_reports') ) {
	    $errors = App\Post::where('post_type', 'error')
	                           ->whereIn('post_status', ['pending'])
	                           ->count();

		$menus['error-reports'] = array(
			'url'   => route($b.'.error-reports.index'),
			'name'  => trans('backend.error_reports'),
			'icon'  => 'fas fa-bug',
			'child' => array(),
			'count' => $errors
		);                          		
    }


	return $menus;
}

function shop_backend_menus() {
	$b = 'backend';
	$f = 'frontend';

    $orders = App\Post::site()
                      ->where('post_type', 'order')
                      ->whereIn('post_status', ['pending', 'on-hold'])
                      ->count();

	$menus = array(
		'url'   => '',
		'name'  => 'Shop',
		'icon'  => 'fas fa-shopping-cart',
		'child' => array()
	);
	$menus['child'][] = array(
		'module' => 'products',
		'role' => [],
		'url'  => route($b.'.shop.products.index'),
		'name' => trans('backend.all_products'),
	);
	$menus['child'][] = array(
		'module' => 'products',
		'role' => ['add_edit'],
		'url'  => route($b.'.shop.products.add'),
		'name' => trans('backend.add_product'),
	);
	$menus['child'][] = array(
		'module' => 'products',
		'role' => ['manage_category'],
		'url'  => route($b.'.categories.index', ['post_type' => 'product-category']),
		'name' => trans('backend.categories'),
	);
	$menus['child'][] = array(
		'module' => 'products',
		'role' => ['manage_attribute'],
		'url'  => route($b.'.categories.index', ['post_type' => 'product-attribute']),
		'name' => 'Attributes',
	);
	$menus['child'][] = array(
		'module' => 'coupons',
		'role' => [],
		'url'  => route($b.'.shop.coupons.index'),
		'name' => trans('backend.coupons'),
	);
	$menus['child'][] = array(
		'module' => 'orders',
		'role' => [],
		'url'  => route($b.'.shop.orders.index', ['post_status' => 'pending']),
		'name' => trans('backend.orders'),
		'count' => $orders,
	);
	$menus['child'][] = array(
		'module' => 'shop',
		'role' => ['settings'],
		'url'  => route($b.'.shop.settings.index'),
		'name' => trans('backend.settings'),
	);
	$menus['child'][] = array(
		'module' => 'shop',
		'role' => ['email_template'],
		'url'  => route($b.'.shop.settings.emails',  ['email' => 'new_order']),
		'name' => trans('backend.shop_emails'),
	);	
	
	return $menus;
}