<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mail, Auth, Request, Input, DB, Session;


class Shop extends Model
{

	//----------------------------------------------------------------	

    public function push_cart($product_id ='', $qty = 1, $type = 'add') {

        $info = Post::site()
                    ->where('post_type', 'product')
                    ->where('id', $product_id)
                    ->where('post_status', 'actived')
                    ->first();

        $pid = $variant_id = $product_id;

        if( $info ) {
            foreach ($info->postmetas as $postmeta) {
                $info[$postmeta->meta_key] = $postmeta->meta_value;
            }
        }   

        $variation_data = array();

        $attributes = Input::except(['_token', 'add-to-cart', 'qty']);


        asort( $attributes );
        $variation_key = implode('-', $attributes);

        if( @$info->variation_data ) {
            foreach (json_decode($info->variation_data, true ) as $variation) {

                asort( $variation['key'] );

                if( implode('-', $variation['key']) ==  $variation_key) {
                    $info->sale_date_start = $variation['field']['sale_date_start'];
                    $info->sale_time_start = $variation['field']['sale_time_start'];
                    $info->sale_date_end = $variation['field']['sale_date_end'];
                    $info->sale_time_end = $variation['field']['sale_time_end'];
                    $info->regular_price = $variation['field']['regular_price'];
                    $info->sale_price    = $variation['field']['sale_price'];
                    $info->sku           = $variation['field']['sku'];

                    $variant_id = $variation['field']['id'];
                }
            }            
        }

        if( $type == 'add' ) {
            $product_id = $product_id.'_'.$variation_key;
        } 

        $price = has_discount($info) ? @$info->sale_price : @$info->regular_price; 

        $cart = Session::get('cart');

        $quantity = $qty + array_get($cart, 'orders.'.$product_id.'.quantity', 0);

        if( $type == 'update' ) {
            $quantity =  $qty;            
            $item_price = array_get($cart, 'orders.'.$product_id.'.item_price', 0);

            $cart['orders'][$product_id]['quantity']    = $quantity;
            $cart['orders'][$product_id]['total_price'] = $quantity * $item_price;
        }

        if( $type == 'add' ) {

	        // Rental 
	        $dd = $attributes['delivery_date'];
			$rd = $attributes['return_date'];
	        unset($attributes['delivery_date']);
	        unset($attributes['return_date']);

            $cart['orders'][$product_id] = array(
                'pid'             => $pid,
                'variant_id'      => $variant_id,
                'id'              => $product_id,
                'sku'             => @$info->sku,
                'name'            => trans_post($info, 'post_title', '_title'),
                'slug'            => @$info->post_name,
                'image'           => @$info->image,
                'quantity'        => $quantity,
                'item_price'      => $price,
                'total_price'     => $quantity * $price,
                'variation_data'  => json_encode($attributes)
            );   

            // Rental 
        	$cart['orders'][$product_id]['delivery_date'] = date_formatted_b($dd);
        	$cart['orders'][$product_id]['return_date']   = date_formatted_b($rd);
            $cart['orders'][$product_id]['delivery_time'] = '11:00-20:00';
        }

        if($quantity==0) {
            array_forget($cart, 'orders.'.$product_id);
        }
        
        $cart['delivery_fee']   = $cart['delivery_fee'] ?? 0;
        $cart['discount_fee']   = $cart['discount_fee'] ?? 0;
        $cart['quantity']       = array_sum(data_get($cart, 'orders.*.quantity'));
        $cart['subtotal']       = array_sum(data_get($cart, 'orders.*.total_price'));
        // $cart['total']          = array_sum(data_get($cart, 'orders.*.total_price'));
        $cart['total']          = ($cart['subtotal'] - @$cart['discount_fee']) + $cart['delivery_fee'];

        if( $cart['total'] <= 0 ) {
            $cart['total'] = $cart['subtotal'] + $cart['delivery_fee'];
            $cart['discount_fee'] = 0;
            unset($cart['coupon_code']);
        }

        session(['cart' => $cart]);
    }

    //----------------------------------------------------------------

}
