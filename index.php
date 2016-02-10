<?php 
require __DIR__.'/conf.php';
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
if(!session_id()) session_start();
global $db;
$shop = $_REQUEST['shop'];
$shop_exists = pg_query($db, "SELECT * FROM configuration WHERE store = '{$shop}'");
if(pg_num_rows($shop_exists) < 1){
	$lastRow = pg_query($db, "SELECT id FROM configuration ORDER by id DESC limit 1");
	$lastID = pg_fetch_assoc($lastRow);
	echo "<pre>";
	print_R($lastID);
	die;
	pg_query($db, "INSERT INTO configuration (id, store, data) VALUES ('{}', '', ' ')");
	$access_token = shopify\access_token($_REQUEST['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
	$url = "https://smsappstore.myshopify.com/admin/webhooks.json";
	$topics = array(
			'customers/create' => 'https://smscountry.herokuapp.com/notify.php?action=customer_signup',
			'orders/create' => 'https://smscountry.herokuapp.com/notify.php?action=order_created',
			'orders/updated' => 'https://smscountry.herokuapp.com/notify.php?action=order_updated',
			'app/uninstalled' => 'https://smscountry.herokuapp.com/notify.php?action=app_uninstalled',
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
	echo "<script>window.top.location = 'https://smsappstore.myshopify.com/admin/apps/smscountry?conf=200';</script>";
	exit();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Settings - SMS Country</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="col-xs-12">
			<div class="container well">
				<?php if(isset($_GET['conf']) && ($_GET['conf'] == 200)){ ?>
					<div class="alert alert-fade alert-success">App installed successfully!</div>
				<?php } ?>
				<ul class="tabs">
					<li><a href="#tab-1">Customer SMS Alerts</a></li>
					<li><a href="#tab-2">Admin SMS Alerts</a></li>
				</ul>
				<div id="tab-1">
					content 1
				</div>
				<div id="tab-2">
					content 2
				</div>     
			</div>
		</div>
	</body>
</html>
