<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('conf.php');
global $db;
/* $updated = print_r($_REQUEST, true); */
/* pg_query($db, "UPDATE debug SET value = '{$updated}' WHERE key = 'updated'"); */
$action = explode('::', $_REQUEST['action']);
$action = $action[0];
$store = $action[1];
$config = pg_query($db, "SELECT data FROM configuration WHERE store = '{$store}'");
$config = pg_fetch_assoc($config);
echo "<pre>";
print_R($config);
die;
/* $config = unserialize($config['data']);
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
			$result = pg_query($db, "SELECT * FROM debug WHERE key = 'customer_signup' ORDER BY id ASC");
			$data = pg_fetch_assoc($result);
			$data = json_decode($data['value']);
			if(!empty($data->default_address->phone)){
				$recipient_name = $data->default_address->name;
				$customerMessage = $config['SMSHTML']['CustomerCustomerSignup'];
				if(!empty($customerMessage)){
					$customVariables = array(
							'[shop_name]' => $storeData->shop->name,
							'[shop_domain]' => $storeData->shop->domain,
							'[customer_firstname]' => $data->first_name,
							'[customer_lastname]' => $data->last_name,
						);
					foreach($customVariables as $find => $replace){
						$customerMessage = str_replace($find, $replace, $customerMessage);
					}
					sendMessage($customerMessage, $data->default_address->phone, $recipient_name, 'CustomerCustomerSignup');
				}
			}
			if(!empty($storeData->shop->phone)){
				$adminMessage = $config['SMSHTML']['AdminCustomerSignup'];
				if(!empty($adminMessage)){
					$customVariables = array(
							'[shop_name]' => $storeData->shop->name,
							'[shop_domain]' => $storeData->shop->domain,
							'[customer_firstname]' => $data->first_name,
							'[customer_lastname]' => $data->last_name,
						);
					foreach($customVariables as $find => $replace){
						$adminMessage = str_replace($find, $replace, $adminMessage);
					}
					sendMessage($adminMessage, $storeData->shop->phone, $storeData->shop->shop_owner, 'AdminCustomerSignup');
				}
			}
			// pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'customer_signup'");
			break;
		case 'order_created':
			pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'order_placed'");
			break;
		case 'order_updated':
			pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'order_status_changed'");
			break;
		case 'app_uninstalled':
			pg_query($db, "DELETE FROM configuration WHERE store = '{$store}'");
			break;
		case 'debug':
			$result = pg_query($db, "SELECT * FROM debug ORDER BY id ASC");
			if(pg_num_rows($result)){
				while($response = pg_fetch_assoc($result)){
					$json = $response['value'];
					if(!empty($json)){
						echo "{$response['key']}:: <pre>";
						print_R($json);
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
exit('Query executed!'); */
?>
