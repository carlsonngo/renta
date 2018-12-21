<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('artisan', function() {
	if($call = Input::get('call')) {
	    Artisan::call($call);
		echo "$ php artisan has been performed!";		
	}
});	

Route::get('fresh-install', function() {
	if( ! Schema::hasTable('settings') ) {
		Artisan::call('migrate:fresh');     
		Artisan::call('migrate');  
		Artisan::call('db:seed');  		
	}
	return redirect()->route('login');
});	


init_settings();

include('shop.php');

/* BEGIN MINIFIED CSS */
Route::get('minified/css', function(){
	minified_css('css/', 'frontend');
	minified_css('css/', 'backend');	
	echo 'CSS successfully minified!';
	exit;
});
/* END MINIFIED CSS */

/* BEGIN HOME - FRONT PAGE */
Route::any('/', [
    'as'   => 'frontend.home', 
    'uses' => 'FrontendController@home'
]);
/* END HOME - FRONT PAGE */

/* BEGIN CONTACT PAGE */
Route::any('contact-us', [
    'as'   => 'frontend.contact', 
    'uses' => 'FrontendController@contact'
]);
/* END CONTACT PAGE */

/* BEGIN GALLERIES PAGE */
Route::group(['prefix' => 'galleries'], function() {
	Route::any('/', [
	    'as'   => 'frontend.galleries', 
	    'uses' => 'FrontendController@galleries'
	]);
	Route::any('gallery/{id?}', [
	    'as'   => 'frontend.gallery', 
	    'uses' => 'FrontendController@gallery'
	]);
});
/* END GALLERIES PAGE */

/* BEGIN AUTH */
Route::group(['prefix' => 'auth'], function() {
	Route::any('/', [
	    'as'   => 'login', 
	    'uses' => 'AuthController@login'
	]);
	Route::any('login', [
	    'as'   => 'auth.login', 
	    'uses' => 'AuthController@login'
	]);
	Route::any('register', [
	    'as'   => 'auth.register', 
	    'uses' => 'AuthController@register'
	]);
	Route::any('verify/{token?}', [
	    'as'   => 'auth.verify', 
	    'uses' => 'AuthController@verify'
	]);
	Route::any('logout', [
	    'as'   => 'auth.logout', 
	    'uses' => 'AuthController@logout'
	]);
	Route::any('forgot-password/{token?}', [
	    'as'   => 'auth.forgot-password', 
	    'uses' => 'AuthController@forgotPassword'
	]);
});
/* END AUTH */


/* BEGIN AUTH - ADMIN */
Route::group(['prefix' => 'b', 'middleware' => ['auth']], function() {

	Route::any('dashboard', [
	    'as'   => 'backend.general.dashboard', 
	    'uses' => 'GeneralController@dashboard'
	]);

	Route::any('note/{id?}/{delete?}', [
	    'as'   => 'backend.general.note', 
	    'uses' => 'GeneralController@note'
	]);

	/* BEGIN USERS */
	Route::group(['prefix' => 'users'], function() {
		
		$module = 'users';

		Route::any('/', [
		    'as'   => 'backend.'.$module.'.index', 
		    'uses' => 'UserController@index'
		])->middleware('access:'.$module);

		Route::any('add', [
		    'as'   => 'backend.'.$module.'.add', 
		    'uses' => 'UserController@add'
		])->middleware('access:'.$module.',add_edit');

		Route::any('edit/{id?}', [
		    'as'   => 'backend.'.$module.'.edit', 
		    'uses' => 'UserController@edit'
		])->middleware('access:'.$module.',add_edit');

		Route::any('delete/{id?}', [
		    'as'   => 'backend.'.$module.'.delete', 
		    'uses' => 'UserController@delete'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('restore/{id?}', [
		    'as'   => 'backend.'.$module.'.restore', 
		    'uses' => 'UserController@restore'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('destroy/{id?}', [
		    'as'   => 'backend.'.$module.'.destroy', 
		    'uses' => 'UserController@destroy'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('account', [
		    'as'   => 'backend.'.$module.'.account', 
		    'uses' => 'UserController@account'
		]);

		Route::any('login/{id?}', [
		    'as'   => 'backend.'.$module.'.login', 
		    'uses' => 'UserController@login'
		])->middleware('access:'.$module.',login_as');
	});
	/* END USERS */


	/* START GROUPS */
	Route::group(['prefix' => 'groups'], function() {
		
		$module = 'groups';

		Route::any('/', [
		    'as'   => 'backend.'.$module.'.index', 
		    'uses' => 'GroupController@index'
		])->middleware('access:'.$module);

		Route::any('add', [
		    'as'   => 'backend.'.$module.'.add', 
		    'uses' => 'GroupController@add'
		])->middleware('access:'.$module.',add_edit');

		Route::any('edit/{id?}', [
		    'as'   => 'backend.'.$module.'.edit', 
		    'uses' => 'GroupController@edit'
		])->middleware('access:'.$module.',add_edit');

		Route::any('delete/{id?}', [
		    'as'   => 'backend.'.$module.'.delete', 
		    'uses' => 'GroupController@delete'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('restore/{id?}', [
		    'as'   => 'backend.'.$module.'.restore', 
		    'uses' => 'GroupController@restore'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('destroy/{id?}', [
		    'as'   => 'backend.'.$module.'.destroy', 
		    'uses' => 'GroupController@destroy'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('permissions/{id?}', [
		    'as'   => 'backend.'.$module.'.permissions', 
		    'uses' => 'GroupController@permissions'
		])->middleware('access:'.$module.',permission');
	});
	/* END GROUPS */

	/* START MEMBERSHIP */
	Route::group(['prefix' => 'memberships'], function() {
		
		$module = 'memberships';

		Route::any('/', [
		    'as'   => 'backend.'.$module.'.index', 
		    'uses' => 'MembershipController@index'
		])->middleware('access:'.$module);

		Route::any('add', [
		    'as'   => 'backend.'.$module.'.add', 
		    'uses' => 'MembershipController@add'
		])->middleware('access:'.$module.',add_edit');

		Route::any('edit/{id?}', [
		    'as'   => 'backend.'.$module.'.edit', 
		    'uses' => 'MembershipController@edit'
		])->middleware('access:'.$module.',add_edit');

		Route::any('delete/{id?}', [
		    'as'   => 'backend.'.$module.'.delete', 
		    'uses' => 'MembershipController@delete'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('restore/{id?}', [
		    'as'   => 'backend.'.$module.'.restore', 
		    'uses' => 'MembershipController@restore'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('destroy/{id?}', [
		    'as'   => 'backend.'.$module.'.destroy', 
		    'uses' => 'MembershipController@destroy'
		])->middleware('access:'.$module.',trash_restore');
	});
	/* END MEMBERSHIP */

	/* BEGIN MEDIA */
	Route::group(['prefix' => 'media'], function() {
		$module = 'media';
		Route::any('/', [
		    'as'   => 'backend.'.$module.'.index', 
		    'uses' => 'MediaController@index'
		])->middleware('access:'.$module);

		Route::any('add', [
		    'as'   => 'backend.'.$module.'.add', 
		    'uses' => 'MediaController@add'
		])->middleware('access:'.$module.',add');

		Route::any('upload', [
		    'as'   => 'backend.'.$module.'.upload', 
		    'uses' => 'MediaController@upload'
		])->middleware('access:'.$module.',add');

		Route::any('unlink', [
		    'as'   => 'backend.'.$module.'.unlink', 
		    'uses' => 'MediaController@unlink'
		])->middleware('access:'.$module.',trash');

		Route::any('frame', [
		    'as'   => 'backend.'.$module.'.frame', 
		    'uses' => 'MediaController@frame'
		])->middleware('access:'.$module.'');

		Route::any('frame/add', [
		    'as'   => 'backend.'.$module.'.frame-add', 
		    'uses' => 'MediaController@frame_add'
		])->middleware('access:'.$module.',add');
	});
	/* END MEDIA */

	$category_type = explode('-', Input::get('post_type'));
	$category = str_plural(@$category_type[0]);

	/* BEGIN CATEGORIES */
	Route::group(['prefix' => 'categories', 
		'middleware' => ['access:'.$category.',manage_'.@$category_type[1]]
	], function() {
		Route::any('/', [
		    'as'   => 'backend.categories.index', 
		    'uses' => 'CategoryController@index'
		]);
		Route::any('add', [
		    'as'   => 'backend.categories.add', 
		    'uses' => 'CategoryController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'backend.categories.edit', 
		    'uses' => 'CategoryController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'backend.categories.delete', 
		    'uses' => 'CategoryController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'backend.categories.restore', 
		    'uses' => 'CategoryController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'backend.categories.destroy', 
		    'uses' => 'CategoryController@destroy'
		]);
	});
	/* END CATEGORIES */
	


	/* BEGIN POSTS */
	Route::group(['prefix' => 'posts'], function() {
		
		$module = str_plural(Input::get('post_type', 'post'));

		Route::any('/', [
		    'as'   => 'backend.posts.index', 
		    'uses' => 'PostController@index'
		])->middleware('access:'.$module);

		Route::any('add', [
		    'as'   => 'backend.posts.add', 
		    'uses' => 'PostController@add'
		])->middleware('access:'.$module.',add_edit');

		Route::any('edit/{id?}', [
		    'as'   => 'backend.posts.edit', 
		    'uses' => 'PostController@edit'
		])->middleware('access:'.$module.',add_edit');

		Route::any('delete/{id?}', [
		    'as'   => 'backend.posts.delete', 
		    'uses' => 'PostController@delete'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('restore/{id?}', [
		    'as'   => 'backend.posts.restore', 
		    'uses' => 'PostController@restore'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('destroy/{id?}', [
		    'as'   => 'backend.posts.destroy', 
		    'uses' => 'PostController@destroy'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('clone/{id?}', [
		    'as'   => 'backend.posts.clone', 
		    'uses' => 'PostController@clone'
		])->middleware('access:'.$module.',duplicate');
	});
	/* END POSTS */

	/* BEGIN DOMAINS */
	Route::group(['prefix' => 'domains'], function() {

		$module = 'domains';

		Route::any('/', [
		    'as'   => 'backend.'.$module.'.index', 
		    'uses' => 'DomainController@index'
		])->middleware('access:'.$module);

		Route::any('add', [
		    'as'   => 'backend.'.$module.'.add', 
		    'uses' => 'DomainController@add'
		])->middleware('access:'.$module.',add_edit');

		Route::any('edit/{id?}', [
		    'as'   => 'backend.'.$module.'.edit', 
		    'uses' => 'DomainController@edit'
		])->middleware('access:'.$module.',add_edit');

		Route::any('delete/{id?}', [
		    'as'   => 'backend.'.$module.'.delete', 
		    'uses' => 'DomainController@delete'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('restore/{id?}', [
		    'as'   => 'backend.'.$module.'.restore', 
		    'uses' => 'DomainController@restore'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('destroy/{id?}', [
		    'as'   => 'backend.'.$module.'.destroy', 
		    'uses' => 'DomainController@destroy'
		])->middleware('access:'.$module.',trash_restore');

	});
	/* END DOMAINS */

	/* BEGIN GALLERY */
	Route::group(['prefix' => 'galleries'], function() {

		$module = 'galleries';

		Route::any('/', [
		    'as'   => 'backend.'.$module.'.index', 
		    'uses' => 'GalleryController@index'
		])->middleware('access:'.$module);

		Route::any('add', [
		    'as'   => 'backend.'.$module.'.add', 
		    'uses' => 'GalleryController@add'
		])->middleware('access:'.$module.',add_edit');

		Route::any('edit/{id?}', [
		    'as'   => 'backend.'.$module.'.edit', 
		    'uses' => 'GalleryController@edit'
		])->middleware('access:'.$module.',add_edit');

		Route::any('delete/{id?}', [
		    'as'   => 'backend.'.$module.'.delete', 
		    'uses' => 'GalleryController@delete'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('restore/{id?}', [
		    'as'   => 'backend.'.$module.'.restore', 
		    'uses' => 'GalleryController@restore'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('destroy/{id?}', [
		    'as'   => 'backend.'.$module.'.destroy', 
		    'uses' => 'GalleryController@destroy'
		])->middleware('access:'.$module.',trash_restore');
	});
	/* END GALLERY */

	/* BEGIN SETTINGS */
	Route::group(['prefix' => 'settings'], function() {
		
		$module = 'settings';

		Route::any('general', [
		    'as'   => 'backend.'.$module.'.general', 
		    'uses' => 'SettingController@general'
		])->middleware('access:'.$module.',general');

		Route::any('menus', [
		    'as'   => 'backend.'.$module.'.menus', 
		    'uses' => 'SettingController@menus'
		])->middleware('access:'.$module.',menu');

		Route::any('menus/add', [
		    'as'   => 'backend.'.$module.'.menus-add', 
		    'uses' => 'SettingController@menus_add'
		])->middleware('access:'.$module.',menu');

		Route::any('menus/save', [
		    'as'   => 'backend.'.$module.'.menus-save', 
		    'uses' => 'SettingController@menus_save'
		])->middleware('access:'.$module.',menu');

		Route::any('localization', [
		    'as'   => 'backend.'.$module.'.localization', 
		    'uses' => 'SettingController@localization'
		])->middleware('access:'.$module.',localization');

		Route::any('localization/export', [
		    'as'   => 'backend.'.$module.'.localization-export', 
		    'uses' => 'SettingController@localization_export'
		])->middleware('access:'.$module.',localization');

		Route::any('localization/import', [
		    'as'   => 'backend.'.$module.'.localization-import', 
		    'uses' => 'SettingController@localization_import'
		])->middleware('access:'.$module.',localization');

		Route::any('slider', [
		    'as'   => 'backend.'.$module.'.slider', 
		    'uses' => 'SettingController@slider'
		])->middleware('access:'.$module.',slider');

		Route::any('css-theme', [
		    'as'   => 'backend.'.$module.'.css-theme', 
		    'uses' => 'SettingController@css_theme'
		])->middleware('access:'.$module.',theme_style');

		Route::any('emails', [
		    'as'   => 'backend.'.$module.'.emails', 
		    'uses' => 'SettingController@emails'
		])->middleware('access:'.$module.',email_template');
	});
	/* END SETTINGS */


	/* BEGIN EVENT POSTS */
	Route::group(['prefix' => 'bookings'], function() {

		$module = 'bookings';

		Route::any('/', [
		    'as'   => 'backend.'.$module.'.index', 
		    'uses' => 'BookingController@index'
		])->middleware('access:'.$module);

		Route::any('add/{id?}', [
		    'as'   => 'backend.'.$module.'.add', 
		    'uses' => 'BookingController@add'
		])->middleware('access:'.$module.',book_now');

		Route::any('delete/{id?}', [
		    'as'   => 'backend.'.$module.'.delete', 
		    'uses' => 'BookingController@delete'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('restore/{id?}', [
		    'as'   => 'backend.'.$module.'.restore', 
		    'uses' => 'BookingController@restore'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('destroy/{id?}', [
		    'as'   => 'backend.'.$module.'.destroy', 
		    'uses' => 'BookingController@destroy'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('calendar', [
		    'as'   => 'backend.'.$module.'.calendar', 
		    'uses' => 'BookingController@calendar'
		])->middleware('access:'.$module.',calendar');

		Route::any('view/{id?}', [
		    'as'   => 'backend.'.$module.'.view', 
		    'uses' => 'BookingController@view'
		])->middleware('access:'.$module.',view');

		Route::any('report', [
		    'as'   => 'backend.'.$module.'.report', 
		    'uses' => 'BookingController@report'
		])->middleware('access:'.$module.',report');
	});
	/* END EVENT POSTS */

	/* BEGIN ERROR REPORTS  */
	Route::group(['prefix' => 'error-reports'], function() {
		
		$module = 'error-reports';

		Route::any('/', [
		    'as'   => 'backend.'.$module.'.index', 
		    'uses' => 'ErrorReportController@index'
		])->middleware('access:'.$module);

		Route::any('edit/{id?}', [
		    'as'   => 'backend.'.$module.'.edit', 
		    'uses' => 'ErrorReportController@edit'
		])->middleware('access:'.$module);

		Route::any('delete/{id?}', [
		    'as'   => 'backend.'.$module.'.delete', 
		    'uses' => 'ErrorReportController@delete'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('restore/{id?}', [
		    'as'   => 'backend.'.$module.'.restore', 
		    'uses' => 'ErrorReportController@restore'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('destroy/{id?}', [
		    'as'   => 'backend.'.$module.'.destroy', 
		    'uses' => 'ErrorReportController@destroy'
		])->middleware('access:'.$module.',trash_restore');
	});
	/* END ERROR REPORTS */

});
/* END AUTH - ADMIN */

Route::any('error-reports/add', [
    'as'   => 'backend.error-reports.add', 
    'uses' => 'ErrorReportController@add'
]);

/* BEGIN EVENT POSTS */
Route::group(['prefix' => 'events'], function() {
	Route::any('/', [
	    'as'   => 'frontend.events.index', 
	    'uses' => 'EventController@index'
	]);
	Route::any('calendar', [
	    'as'   => 'frontend.events.calendar', 
	    'uses' => 'EventController@calendar'
	]);	
	Route::any('verify/{code?}', [
	    'as'   => 'frontend.events.verify', 
	    'uses' => 'EventController@verify'
	]);	
	Route::any('ticket/{code?}', [
	    'as'   => 'frontend.events.ticket', 
	    'uses' => 'EventController@ticket'
	]);	
	Route::any('{name?}', [
	    'as'   => 'frontend.events.single', 
	    'uses' => 'EventController@single'
	]);	
});
/* END EVENT POSTS */


/* BEGIN BLOG POSTS */
Route::any('{categories?}/{slug?}', [
    'as'   => 'frontend.post', 
    'uses' => 'PostController@post'
]);
/* END BLOG POSTS */

