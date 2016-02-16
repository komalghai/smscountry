<?php 
require('conf.php');
global $db;
$action = $_REQUEST['action'];
if(!empty($action)){
	switch($action){
		case 'saveSMS':
			$key = $_REQUEST['key']; 
			$value = htmlentities(addcslashes($_REQUEST['value']));
			$store = $_REQUEST['store'];
			if(!empty($key) && !empty($value)){
				$config = pg_query($db, "SELECT data FROM configuration WHERE store = '{$store}'");
				$config = pg_fetch_assoc($config);
				$config = unserialize($config['data']);
				$config['SMSHTML'][$key] = $value;
				/* $config = serialize($config); */
				echo "<pre>";
				print_r($config);
				die;
				$updated = pg_query($db, "UPDATE configuration SET data = '{$config}' WHERE store = '{$store}'");
				if($updated){
					exit('1');
				} else {
					exit('0');
				}
			}
			break;

		case 'sendTestSMS':
			$shop = $_REQUEST['shop'];
			$domain = $_REQUEST['domain'];
			$message = $_REQUEST['message'];
			$mobilenumber = $_REQUEST['mobilenumber'];
			$customVariables = array(
					'[shop_name]' => $shop,
					'[shop_domain]' => $domain,
					'[customer_count]' => '36',
					'[customer_firstname]' => 'John',
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
					'[customer_message]' => 'Hi, This message is regarding testing.',
				);
			foreach($customVariables as $find => $replace){
				$message = str_replace($find, $replace, $message);
			}
			$message = urlencode($message);
			sendTestMessage($message, $mobilenumber);
			break;
			
		default:
			break;
		
	}
} else {
	exit("ERR: NO ACTION DEFINED!");
}
exit();
