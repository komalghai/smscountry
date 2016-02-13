<?php 
require('conf.php');
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
global $db;
$action = $_REQUEST['action'];
if(!empty($action)){
	switch($action){
		case 'saveSMS':
			$key = $_REQUEST['key']; 
			$value = $_REQUEST['value'];
			$store = $_REQUEST['store'];
			if(!empty($key) && !empty($value)){
				$config = pg_query($db, "SELECT data FROM configuration WHERE store = '{$store}'");
				$config = pg_fetch_assoc($config);
				$config = unserialize($config['data']);
				$config['SMSHTML'][$key] = $value;
				$config = serialize($config);
				if(pg_query($db, "UPDATE configuration SET data = '{$config}' WHERE store = '{$store}'")){
					exit('1');
				} else {
					exit('0');
				}
			}
			break;

		case 'sendTestSMS':
			$store = $_REQUEST['store'];
			$code = $_REQUEST['code'];
			$message = $_REQUEST['message'];
			$access_token = shopify\access_token($store, SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $code);
			$storeData = json_decode(file_get_contents("https://{$store}/admin/shop.json?access_token={$access_token}"));
			$mobilenumber = $storeData->shop->phone;
			$user = SMS_USERNAME;
			$password = SMS_PASSWORD;
			$senderid = SENDER_ID;
			$messagetype = MESSAGE_TYPE;
			$DReports = DELIVERY_REPORT;
			$url = "http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
			$customVariables = array(
					'[shop_name]' => $storeData->shop->name,
					'[shop_domain]' => $storeData->shop->domain,
					'[customer_count]' => '36',
					'[customer_firstname]' => 'Jon',
					'[customer_lastname]' => 'Doe',
					'[verification_code]' => '5623321',
					'[customer_address]' => '1444 S. Alameda Street',
					'[customer_postcode]' => '90021',
					'[customer_city]' => 'Los Angeles',
					'[customer_country]' => 'United States',
					'[order_id]' => '10045',
					'[order_total]' => '$982.00',
					'[order_products_count]' => '3',
					'[order_status]' => 'Pending',
					'[order_old_status]' => 'Pending',
					'[order_new_status]' => 'Fullfilled',
					'[order_date]' => '26th Jan, 2016',
					'[return_product_name]' => 'Nokia v.2',
					'[return_reason]' => 'The seal of package was broken, looks like it was used before.',
					'[customer_name]' => 'John Doe',
					'[customer_email]' => 'johndoe342@mail.com',
					'[customer_message]' => 'Hi , This message is regarding testing.',
				);
			foreach($customVariables as $find => $replace){
				$message = str_replace($find, $replace, $message);
			}
			$message = urlencode($message);
			echo $message;
			die;
			exit('Working :)');
			
			$ch = curl_init();
			if (!$ch){
				die("Couldn't initialize a cURL handle");
			}
			$ret = curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, "User={$user}&passwd={$password}&mobilenumber={$mobilenumber}&message={$message}&sid={$senderid}&mtype={$messagetype}&DR={$DReports}");
			$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$curlresponse = curl_exec($ch);
			if(curl_errno($ch))
			echo 'curl error : '. curl_error($ch);
			if (empty($ret)) {
				die(curl_error($ch));
				curl_close($ch);
			} else {
				$info = curl_getinfo($ch);
				curl_close($ch);
				echo $curlresponse;
			}
			break;
			
		default:
			break;
		
	}
} else {
	exit("ERR: NO ACTION DEFINED!");
}
exit();
