<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mail, Auth, Request, Input, DB, Session;


class GotoPay extends Model
{

	//----------------------------------------------------------------	

    public function process($order_data = '') {

        try {

            $url = 'https://secure.co-server.online/checkout/gotopay.net/PaymentRemote.aspx';

            $salt = '123#@123';
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];

            $order_data = session('order_data');
            $inputs = session('checkout');
            $cart = session('cart');
            list($card_month, $card_year) = explode('/', $inputs['card_expiry']);

            $order_code = $order_data['id'];

            $fast_shipping = $this->handleShipping($cart['shipping_fee']);
            $products = $this->getAllProducts($cart['orders']);

            $card_number = str_replace(' ', '', $inputs['card_number']);

            $form = array(
                'sCountry'          => $inputs['shipping_country'],
                'bCountry'          => $inputs['billing_country'],
                'aid'               => 1442,
                'ordercode'         => $order_code,
                'sState'            => $inputs['shipping_state'],
                'bState'            => $inputs['billing_state'],
                'sCity'             => $inputs['shipping_city'],
                'bCity'             => $inputs['billing_city'],
                'sAddress'          => $inputs['shipping_address_1'],
                'bAddress'          => $inputs['billing_address_1'],
                'sFirstName'        => $inputs['shipping_firstname'],
                'bFirstName'        => $inputs['billing_firstname'],
                'sLastName'         => $inputs['shipping_lastname'],
                'bLastName'         => $inputs['billing_lastname'],
                'sZip'              => $inputs['shipping_zipcode'],
                'bZip'              => $inputs['billing_zipcode'],
                'phone'            => $inputs['shipping_phone'],
                'phone2'            => $inputs['billing_phone'],
                'ipAddress'         => $ip_address,
                'Time'              => str_replace('.0', '', strtotime(date('Y-m-d H:i:s')) * 1000),
                'mail'              => $inputs['billing_email_address'],
                'PaymentType'       => 'direct',
                'PaymentMethod'     => @$inputs['card_type'] ? $inputs['card_type'] : 'unknown',
                'ccNumber'          => $card_number,
                'ccCvv'             => str_replace(' ', '', $inputs['card_cvv']),
                'ccExpirationYear'  => str_replace(' ', '', '20'.$card_year),
                'ccExpirationMonth' => str_replace(' ', '', $card_month),
                'pid'               => implode(',', $products['pid']),
                'qty'               => implode(',', $products['quantity']),
                'checksum'          => md5($salt.$ip_address.$card_number),
                'fastShipping'      => $fast_shipping,
                'coupon'            => '',
                'donotsendmail'     => 'false',
                'userAgent'         => $user_agent,
                'insurance'         => 0,
                'comment'           => $this->get_product_info($cart['orders'])
            );

            dd($form);
            
            return app('App\Http\Controllers\ShopController')->place_order();

            $client = new \GuzzleHttp\Client();

            $res = $client->request('POST', $url, [
                "verify" => false,
                'form_params' => $form,
            ]);

            if( $res->getBody() == "Success my ordercode ".$order_code ) {
                echo 'Success!';   
            } else {
                echo 'failed!';
            }

            $client = new \GuzzleHttp\Client();

        } catch (Exception $e){
            dd($e);
        }

    }

    //----------------------------------------------------------------
    
    private function get_product_info($products) {
        $data = array();

        foreach($products as $p) {

            $p['variation_data'] = json_decode($p['variation_data'], true);

            $variation = str_replace(['{','}'], ['(',')'], json_encode($p['variation_data']));
            $data[] = $p['name'].' ('.$p['quantity'].' x '.amount_formatted($p['item_price']).') '.$variation;
        }

        return implode(' | ', $data);
    }

    //----------------------------------------------------------------

    private function getAllProducts($positions) {
        $productarr = array();
        $prefix = 'gb-';
        $i=1;

        foreach ($positions as $prod){
            $productarr['pid'][] = $prefix.$prod['sku'];
            $productarr['quantity'][] = $prod['quantity'];
            $i++;
        }

        return $productarr;
    }

    //----------------------------------------------------------------

    private function handleShipping($shipping_price){

        if($shipping_price == 0) {
            $data = 265;
        } else {
            $data = 266;
        }

        return $data;
    }

    //----------------------------------------------------------------

    function check_order($order_id ='', $zip ='') {
        $url = 'https://utils.cash4aff.com/api/orderstatus';

        $client = new \GuzzleHttp\Client();
        
        $data = array("orderCode" => $order_id, "zip" => $zip);

        $res = $client->request('POST', $url, [
            "verify"  => false,
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => '['.json_encode($data).']',
        ]);

        return @json_decode($res->getBody())[0];
    }

    //----------------------------------------------------------------

}
