<?php
Route::group(['prefix' => 'shop'], function() {
	Route::any('/', [
	    'as'   => 'shop.index', 
	    'uses' => 'ShopController@index'
	]);
	Route::any('add-to-cart', [
	    'as'   => 'shop.add_to_cart', 
	    'uses' => 'ShopController@add_to_cart'
	]);
	Route::any('cart', [
	    'as'   => 'shop.cart', 
	    'uses' => 'ShopController@cart'
	]);
	Route::any('checkout', [
	    'as'   => 'shop.checkout', 
	    'uses' => 'ShopController@checkout'
	]);
	Route::any('place-order', [
	    'as'   => 'shop.place-order', 
	    'uses' => 'ShopController@place_order'
	]);
	Route::any('checkout/completed/{token?}', [
	    'as'   => 'shop.completed', 
	    'uses' => 'ShopController@completed'
	]);
	Route::any('checkout/remove-item/{id?}', [
	    'as'   => 'shop.remove-item', 
	    'uses' => 'ShopController@remove_item'
	]);
	Route::any('{name?}', [
	    'as'   => 'shop.single', 
	    'uses' => 'ShopController@single'
	]);
	Route::any('reviews/{id?}', [
	    'as'   => 'shop.reviews', 
	    'uses' => 'ShopController@reviews'
	]);
	Route::any('cart/totals', [
	    'as'   => 'shop.cart.totals', 
	    'uses' => 'ShopController@cart_totals'
	]);
	Route::any('update/delivery', [
	    'as'   => 'shop.cart.update-delivery', 
	    'uses' => 'ShopController@update_delivery'
	]);
});

Route::group(['prefix' => 'api'], function() {
	Route::get('check-orders', [
	    'as'   => 'shop.api.check_orders', 
	    'uses' => 'ShopController@check_orders'
	]);
});

/* BEGIN AUTH - ADMIN */
Route::group(['prefix' => 'customer'], function() {
	Route::any('/', [
	    'as'   => 'shop.customer.index', 
	    'uses' => 'CustomerController@index'
	]);
	Route::any('addresses', [
	    'as'   => 'shop.customer.addresses', 
	    'uses' => 'CustomerController@addresses'
	]);
	Route::any('account', [
	    'as'   => 'shop.customer.account', 
	    'uses' => 'CustomerController@account'
	]);
	Route::group(['prefix' => 'orders'], function() {
		Route::any('/', [
		    'as'   => 'shop.customer.orders', 
		    'uses' => 'CustomerController@orders'
		]);
		Route::any('{id?}', [
		    'as'   => 'shop.customer.order-view', 
		    'uses' => 'CustomerController@order_view'
		]);
	});
});
/* END AUTH - ADMIN */

/* BEGIN AUTH - ADMIN */
Route::group(['prefix' => 'b/shop', 'middleware' => ['auth']], function() {

	Route::group(['prefix' => 'settings'], function() {

		Route::any('/', [
		    'as'   => 'backend.shop.settings.index', 
		    'uses' => 'ShopController@settings'
		])->middleware('access:shop,settings');

		Route::any('emails', [
		    'as'   => 'backend.shop.settings.emails', 
		    'uses' => 'ShopController@emails'
		])->middleware('access:shop,email_template');

	});

	Route::group(['prefix' => 'products'], function() {
		
		$module = 'products';

		Route::any('/', [
		    'as'   => 'backend.shop.'.$module.'.index', 
		    'uses' => 'ProductController@index'
		])->middleware('access:'.$module);

		Route::any('add', [
		    'as'   => 'backend.shop.'.$module.'.add', 
		    'uses' => 'ProductController@add'
		])->middleware('access:'.$module.',add_edit');

		Route::any('edit/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.edit', 
		    'uses' => 'ProductController@edit'
		])->middleware('access:'.$module.',add_edit');

		Route::any('delete/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.delete', 
		    'uses' => 'ProductController@delete'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('restore/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.restore', 
		    'uses' => 'ProductController@restore'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('destroy/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.destroy', 
		    'uses' => 'ProductController@destroy'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('data/attributes', [
		    'as'   => 'backend.shop.'.$module.'.data-attributes', 
		    'uses' => 'ProductController@data_attributes'
		]);

		Route::any('data/variations', [
		    'as'   => 'backend.shop.'.$module.'.data-variations', 
		    'uses' => 'ProductController@data_variations'
		]);

		Route::any('import', [
		    'as'   => 'backend.shop.'.$module.'.import', 
		    'uses' => 'ProductController@import'
		]);
		Route::any('export', [
		    'as'   => 'backend.shop.'.$module.'.export', 
		    'uses' => 'ProductController@export'
		]);

	});

	Route::group(['prefix' => 'coupons'], function() {

		$module = 'coupons';

		Route::any('/', [
		    'as'   => 'backend.shop.'.$module.'.index', 
		    'uses' => 'CouponController@index'
		])->middleware('access:'.$module);

		Route::any('add', [
		    'as'   => 'backend.shop.'.$module.'.add', 
		    'uses' => 'CouponController@add'
		])->middleware('access:'.$module.',add_edit');

		Route::any('edit/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.edit', 
		    'uses' => 'CouponController@edit'
		])->middleware('access:'.$module.',add_edit');

		Route::any('delete/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.delete', 
		    'uses' => 'CouponController@delete'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('restore/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.restore', 
		    'uses' => 'CouponController@restore'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('destroy/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.destroy', 
		    'uses' => 'CouponController@destroy'
		])->middleware('access:'.$module.',trash_restore');

	});

	Route::group(['prefix' => 'orders'], function() {
		
		$module = 'orders';

		Route::any('/', [
		    'as'   => 'backend.shop.'.$module.'.index', 
		    'uses' => 'OrderController@index'
		])->middleware('access:'.$module);

		Route::any('edit/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.edit', 
		    'uses' => 'OrderController@edit'
		])->middleware('access:'.$module.',add_edit');

		Route::any('delete/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.delete', 
		    'uses' => 'OrderController@delete'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('restore/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.restore', 
		    'uses' => 'OrderController@restore'
		])->middleware('access:'.$module.',trash_restore');

		Route::any('destroy/{id?}', [
		    'as'   => 'backend.shop.'.$module.'.destroy', 
		    'uses' => 'OrderController@destroy'
		])->middleware('access:'.$module.',trash_restore');

	});
	Route::any('review', [
	    'as'   => 'backend.shop.review', 
	    'uses' => 'ShopController@review'
	]);
});
/* END AUTH - ADMIN */


Route::group(['prefix' => 'coupon'], function() {
	Route::any('remove', [
	    'as'   => 'backend.coupons.remove', 
	    'uses' => 'CouponController@remove'
	]);
	Route::any('apply', [
	    'as'   => 'backend.coupons.apply', 
	    'uses' => 'CouponController@apply'
	]);
});

Route::group(['prefix' => 'paypal'], function() {
	Route::post('direct', [
	    'as'   => 'paypal.direct', 
	    'uses' => 'PaypalController@direct'
	]);
	Route::post('pay', [
	    'as'   => 'paypal.pay', 
	    'uses' => 'PaypalController@pay'
	]);
	Route::get('status', [
	    'as'   => 'paypal.status', 
	    'uses' => 'PaypalController@status'
	]);
	Route::get('checkout', [
	    'as'   => 'paypal.checkout', 
	    'uses' => 'PaypalController@checkout'
	]);
});


