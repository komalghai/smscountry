<?php 
require __DIR__.'/conf.php';
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
if(!session_id()) session_start();
global $db;
$shop = $_REQUEST['shop'];
$shop_exists = pg_query($db, "SELECT * FROM configuration WHERE store = '{$shop}'");
if(pg_num_rows($shop_exists) < 1){
	$access_token = shopify\access_token($_REQUEST['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
	$lastRow = pg_query($db, "SELECT id FROM configuration ORDER by id DESC limit 1");
	$lastID = pg_fetch_assoc($lastRow);
	$lastID = (pg_num_rows($lastRow) > 0) ? $lastID['id'] : 1;
	$data = array(
			'shop_name' => $shop,
			'access_token' => $access_token,
		);
	$data = serialize($data);
	pg_query($db, "INSERT INTO configuration (id, store, data) VALUES ('{$lastID}', '{$shop}', '{$data}')");
	
	$url = "https://{$shop}/admin/webhooks.json";
	$topics = array(
			'customers/create' => "https://smscountry.herokuapp.com/notify.php?store={$shop}&action=customer_signup",
			'orders/create' => "https://smscountry.herokuapp.com/notify.php?action=store={$shop}&order_created",
			'orders/updated' => "https://smscountry.herokuapp.com/notify.php?store={$shop}&action=order_updated",
			'app/uninstalled' => "https://smscountry.herokuapp.com/notify.php?store={$shop}&action=app_uninstalled",
		);
	foreach($topics as $topic => $address){
		$data = array(
				'access_token' => $access_token,
				'webhook' => array(
					'address' => $address,
					'format' => 'json',
					'topic' => $topic,
				)
			);
		$data = json_encode($data);	
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data))                                                                       
		);
		curl_exec($ch);
	}
	echo "<script>window.location = 'https://{$shop}/admin/apps/smscountry/?conf=200';</script>";
	exit();
}
include('settings.php');
?>
