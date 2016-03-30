<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('conf.php');
global $db;
$actionTemp = explode('::', $_REQUEST['action']);
$action = $actionTemp[0];
$store = $actionTemp[1];
$config = pg_query($db, "SELECT data FROM configuration WHERE store = '{$store}'");
$config = pg_fetch_assoc($config);
$config = unserialize($config['data']);
$CustomerSignupsmsactive = isset($config['smsactive']['CustomerCustomerSignup']) ? $config['smsactive']['CustomerCustomerSignup'] : null;
$CustomerSignupVerificationsmsactive = isset($config['smsactive']['CustomerCustomerSignupVerification']) ? $config['smsactive']['CustomerCustomerSignupVerification'] : null;
$CustomerOrderPlacedsmsactive = isset($config['smsactive']['CustomerOrderPlaced']) ? $config['smsactive']['CustomerOrderPlaced'] : null;
$CustomerOrderStatusChangedsmsactive = isset($config['smsactive']['CustomerOrderStatusChanged']) ? $config['smsactive']['CustomerOrderStatusChanged'] : null;
$AdminCustomerSignupsmsactive = isset($config['smsactive']['AdminCustomerSignup']) ? $config['smsactive']['AdminCustomerSignup'] : null;
$AdminCustomerSignupScheduledsmsactive = isset($config['smsactive']['AdminCustomerSignupScheduled']) ? $config['smsactive']['AdminCustomerSignupScheduled'] : null;
$AdminOrderPlacedsmsactive = isset($config['smsactive']['AdminOrderPlaced']) ? $config['smsactive']['AdminOrderPlaced'] : null;
$AdminOrderReturnRequestsmsactive = isset($config['smsactive']['AdminOrderReturnRequest']) ? $config['smsactive']['AdminOrderReturnRequest'] : null;
$AdminContactInquirysmsactive = isset($config['smsactive']['AdminContactInquiry']) ? $config['smsactive']['AdminContactInquiry'] : null;
$access_token = $config['access_token'];
$storeData = json_decode(file_get_contents("https://{$store}/admin/shop.json?access_token={$access_token}"));
if(!empty($action)){
	$data = '';
	$webhook = fopen('php://input' , 'rb'); 
	while(!feof($webhook)){
		$data .= fread($webhook, 4096); 
	}
	fclose($webhook);
	$data = json_decode($data);
	switch($action){
		case 'customer_signup':
			if(!empty($data->default_address->phone) && $CustomerSignupsmsactive=="true" ){
				$recipient_name = $data->default_address->name;
				$customerMessage = str_replace('<br>', '\n', $config['SMSHTML']['CustomerCustomerSignup']);
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
			if(!empty($storeData->shop->phone) && $AdminCustomerSignupsmsactive=="true"){
				$adminMessage = str_replace('<br>', '\n', $config['SMSHTML']['AdminCustomerSignup']);
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
			/* pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'customer_signup'"); */
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
exit('Query executed!');
?>
