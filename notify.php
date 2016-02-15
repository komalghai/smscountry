<?php 
require('conf.php');
if(!session_id()) session_start();
global $db;
$store = $_REQUEST['store'];
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
$config = pg_query($db, "SELECT data FROM configuration WHERE store = '{$store}'");
$config = pg_fetch_assoc($config);
$config = unserialize($config['data']);
$access_token = $config['access_token'];
$storeData = @file_get_contents("https://{$store}/admin/shop.json?access_token={$access_token}");
if(!empty($action)){
	$data = '';
	$webhook = fopen('php://input' , 'rb'); 
	while(!feof($webhook)){
		$data .= fread($webhook, 4096); 
	} 
	fclose($webhook);
	
	switch($action){
		case 'customer_signup':
			/* if(!empty($data->default_address->phone)){
				$recipient_name = $data->default_address->name;
				$customerMessage = $config['SMSHTML']['CustomerCustomerSignup'];
				$customVariables = array(
						'[shop_name]' => $storeData->shop->name,
						'[shop_domain]' => $storeData->shop->domain,
						'[customer_count]' => '36',
						'[customer_firstname]' => $data,
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
				sendMessage($customerMessage, $data->default_address->phone, $recipient_name, 'CustomerCustomerSignup');
			}
			if(!empty($storeData->shop->phone)){
				$adminMessage = $config['SMSHTML']['AdminCustomerSignup'];
				sendMessage($adminMessage, $storeData->shop->phone, $storeData->shop->shop_owner, 'AdminCustomerSignup');
			} */
			pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'customer_signup'");
			break;
		case 'order_created':
			pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'order_placed'");
			break;
		case 'order_updated':
			pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'order_status_changed'");
			break;
		case 'app_uninstalled':
			$store = $data->domain;
			pg_query($db, "DELETE FROM configuration WHERE store = '{$store}'");
			break;
		case 'debug':
			$result = pg_query($db, "SELECT * FROM debug ORDER BY id ASC");
			if(pg_num_rows($result)){
				while($response = pg_fetch_assoc($result)){
					$json = $response['value'];
					if(!empty($json)){
						echo "{$response['key']}:: <pre>";
						print_R(json_decode($json));
						echo "</pre>";
					}
				}
			}
			break;
		case 'config':
			$result = pg_query($db, "SELECT * FROM configuration ORDER BY id ASC");
			if(pg_num_rows($result)){
				while($response = pg_fetch_assoc($result)){
					$json = $response['data'];
					if(!empty($json)){
						echo "{$response['store']}:: <pre>";
						print_R(unserialize($json));
						echo "</pre>";
					}
				}
			}
			break;
		default:
			break;
	}
}
exit('Query executed!');
?>
