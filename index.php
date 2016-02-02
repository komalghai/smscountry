<?php
	if(!session_id()) session_start(); 
	require __DIR__.'/vendor/autoload.php';
	use phpish\shopify;
	require __DIR__.'/conf.php';
	isset($_REQUEST['shop']) or die ('Query parameter "shop" missing.');
	preg_match('/^[a-zA-Z0-9\-]+.myshopify.com$/', $_REQUEST['shop']) or die('Invalid myshopify.com store URL.');
	$oauth_token = shopify\access_token($_REQUEST['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
	$_SESSION['oauth_token'] = $oauth_token;
	$_SESSION['shop'] = $_REQUEST['shop']; 
	
	$data = array(
			'_method' => 'post',
			'access_token' => $oauth_token,
			'authenticity_token' => $oauth_token,
			'utf8' => '✓',
			'webhook' => array(
				'address' => 'https://smscountry.herokuapp.com/',
				'format' => 'json',
				'topic' => 'orders/create',
			)
		);
	$data_string = json_encode($data);																		 
	$ch = curl_init("https://smsappstore.myshopify.com/admin/webhooks.json");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
	);
	$response = curl_exec($ch);
	echo "<pre>";
	print_r($response);
	die;
	echo "<script>window.location = 'https://smsappstore.myshopify.com/admin/apps';</script>";
	exit();
?>
