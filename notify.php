<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('conf.php');
if(!session_id()) session_start();
global $db;
date_default_timezone_set('Asia/Kolkata');

$now = date('Y-m-d H:i:s');
$updated = "Updated: {$now}";
pg_query($db, "UPDATE debug SET value = '{$updated}' WHERE key = 'updated'");

$store = $_REQUEST['store'];
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

$storeCurl = curl_init();
curl_setopt($storeCurl, CURLOPT_URL, 'https://'.$store.'/admin/shop.json');
curl_setopt($storeCurl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($storeCurl, CURLOPT_HEADER, false);
curl_setopt($storeCurl, CURLOPT_POSTFIELDS, 'api_key='.SHOPIFY_APP_API_KEY);
$storeData = curl_exec($storeCurl);
curl_close($storeCurl);
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
	
	$config = pg_query($db, "SELECT data FROM configuration WHERE store = '{$store}'");
	$config = pg_fetch_assoc($config);
	$config = unserialize($config['data']);
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
