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
$sms_admin_phone =$config['sms_admin_phone'];
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
			if(!empty($sms_admin_phone) && $AdminCustomerSignupsmsactive=="true"){
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
					sendMessage($adminMessage,$sms_admin_phone, $storeData->shop->shop_owner, 'AdminCustomerSignup');
				}
			}
			/* pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'customer_signup'"); */
			break;
		case 'order_created':
		if($data->confirmed=='true') {
						$order_status="Order Confirmed";
					}
					else{
						$order_status="Order pending";
					}
			//pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'order_placed'");
			if(!empty($data->default_address->phone) && $CustomerOrderPlacedsmsactive=="true" ){
				$recipient_name = $data->default_address->name;
				$customerMessage = str_replace('<br>', '\n', $config['SMSHTML']['CustomerOrderPlaced']);
				if(!empty($customerMessage)){
					
					$customVariables = array(
							'[shop_name]' => $storeData->shop->name,
							'[shop_domain]' => $storeData->shop->domain,
							'[customer_firstname]' => $data->shipping_address->first_name,
							'[customer_lastname]' => $data->shipping_address->last_name,
							'[customer_address]' => $data->shipping_address->address1,
							'[customer_postcode]'=>$data->shipping_address->zip,
		                    '[customer_city]'=>$data->shipping_address->city,
							'[customer_country]'=>$data->shipping_address->country,
							'[order_id]'=>$data->order_number,
							'[order_total]'=>$data->total_price,
							'[order_products_count]'=>$data->line_items->fulfillable_quantity,
							'[order_status]'=>$order_status,
						);
					foreach($customVariables as $find => $replace){
						$customerMessage = str_replace($find, $replace, $customerMessage);
					}
					sendMessage($customerMessage, $data->default_address->phone, $recipient_name, 'CustomerOrderPlaced');
				}
			}
			if(!empty($sms_admin_phone) && $AdminOrderPlacedsmsactive=="true"){
				$adminMessage = str_replace('<br>', '\n', $config['SMSHTML']['AdminOrderPlaced']);
				if(!empty($adminMessage)){
					$customVariables = array(
							
							'[shop_name]' => $storeData->shop->name,
							'[shop_domain]' => $storeData->shop->domain,
							'[customer_firstname]' => $data->shipping_address->first_name,
							'[customer_lastname]' => $data->shipping_address->last_name,
							'[customer_address]' => $data->shipping_address->address1,
							'[customer_postcode]'=>$data->shipping_address->zip,
		                    '[customer_city]'=>$data->shipping_address->city,
							'[customer_country]'=>$data->shipping_address->country,
							'[order_id]'=>$data->order_number,
							'[order_total]'=>$data->total_price,
							'[order_products_count]'=>$data->line_items->fulfillable_quantity,
							'[order_status]'=>$order_status,
						
						);
					foreach($customVariables as $find => $replace){
						$adminMessage = str_replace($find, $replace, $adminMessage);
					}
					sendMessage($adminMessage, $sms_admin_phone, $storeData->shop->shop_owner, 'AdminOrderPlaced');
				}
			}
			
			break;
		case 'order_updated':
			//$data=serialize($data);
			//pg_query($db, "UPDATE debug SET value = 'testings12356890' WHERE key = 'order_status_changed'");
			if($data->confirmed=='1') {
						$order_status="Order Confirmed";
					}
					else{
						$order_status="Order pending";
					}
					
			if(!empty($data->customer->default_address->phone) && $CustomerOrderStatusChangedsmsactive=="true" ){
				$recipient_name = $data->customer->default_address->first_name;
				$customerMessage = str_replace('<br>', '\n', $config['SMSHTML']['CustomerOrderStatusChanged']);
				if(!empty($customerMessage)){
					
					$customVariables = array(
							'[shop_name]' => $storeData->shop->name,
							'[shop_domain]' => $storeData->shop->domain,
							'[customer_firstname]' => $data->customer->default_address->first_name,
							'[customer_lastname]' => $data->customer->default_address->last_name,
							'[customer_address]' => $data->customer->default_address->address1,
							'[customer_postcode]'=>$data->customer->default_address->zip,
		                    '[customer_city]'=>$data->customer->default_address->city,
							'[customer_country]'=>$data->customer->default_address->country,
							'[order_id]'=>$data->order_number,
							'[order_total]'=>$data->total_price,
							'[order_products_count]'=>$data->line_items->fulfillable_quantity,
							'[order_old_status]'=>$data->order_status,
							'[order_new_status]'=>$data->fulfillment_status,
							'[financial_status]'=>$data->financial_status,
							'[order_date]'=>$data->updated_at,
						);
						foreach($customVariables as $find => $replace){
							$customerMessage = str_replace($find, $replace, $customerMessage);
						}
						
						sendMessage($customerMessage, $data->customer->default_address->phone, $recipient_name, 'CustomerOrderStatusChanged');
						
				}
			}
			
		
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
