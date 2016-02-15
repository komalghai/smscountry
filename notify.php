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
echo "<pre>";
print_r($storeData);
die;
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
				sendMessage($customerMessage, $data->default_address->phone, $recipient_name, 'CustomerCustomerSignup');
			}
			if(!empty()){
				$adminMessage = $config['SMSHTML']['AdminCustomerSignup'];
				sendMessage($adminMessage, $mobilenumber, $recipient_name, 'AdminCustomerSignup');
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
