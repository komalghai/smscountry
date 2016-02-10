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
	$lastID = (pg_num_rows($lastRow) > 0) ? $lastID['id'] : 1;
	$data = array(
			'shop_name' => $shop,
		);
	$data = serialize($data);
	pg_query($db, "INSERT INTO configuration (id, store, data) VALUES ('{$lastID}', '{$shop}', '{$data}')");
	$access_token = shopify\access_token($_REQUEST['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
	$url = "https://{$shop}/admin/webhooks.json";
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
	echo "<script>window.location = 'https://{$shop}/admin/apps/smscountry/?conf=200';</script>";
	exit();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Configuration - SMS Country</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<style type="text/css">
			div.nav-content{
				display: none;
			}
		</style>
	</head>
	<body>
		<div class="col-xs-12 padding-top sms-config">
			<h3 class="alert alert-info">Configuration</h3>
			<form>
				<div class="box container well col-xs-12" style="padding: 0">
					<ul class="tabs nav">
						<li class="col-xs-6 active"><a href="#customer-sms-alerts">Customer SMS Alerts</a></li>
						<li class="col-xs-6"><a href="#admin-sms-alerts">Admin SMS Alerts</a></li>
					</ul>
					<div id="customer-sms-alerts" class="nav-content" style="display: block;">
						<div class="col-xs-4">
							<h4>New Customer Signup:</h4>
							<code>
								You can use following variables: [shop_name], [shop_domain], [customer_firstname], [customer_lastname].
							</code>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label for="email"></label>
								<input type="email" class="form-control" id="email">
							</div>
						</div>
						<div class="col-xs-2">
							<button class="btn btn-info">Send Test SMS</button>
						</div>
					</div>
					<div id="admin-sms-alerts" class="nav-content">
						<div class="col-xs-4">
							<h4>New Customer Signup:</h4>
							<code>
								You can use following variables: [shop_name], [shop_domain], [customer_firstname], [customer_lastname].
							</code>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label for="email"></label>
								<input type="email" class="form-control" id="email">
							</div>
						</div>
						<div class="col-xs-2">
							<button class="btn btn-info">Send Test SMS</button>
						</div>
					</div>   
					<button type="submit" class="btn btn-success">Submit</button>				
				</div>
			</form>
		</div>
	</body>
</html>
