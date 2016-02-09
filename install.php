<?php
	require __DIR__.'/vendor/autoload.php';
	use phpish\shopify;
	require __DIR__.'/conf.php';
	isset($_REQUEST['shop']) or die ('Query parameter "shop" missing.');
	preg_match('/^[a-zA-Z0-9\-]+.myshopify.com$/', $_REQUEST['shop']) or die('Invalid myshopify.com store URL.');
	
	$access_token = shopify\access_token($_REQUEST['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
	$_SESSION['oauth_token'] = $access_token;
	$_SESSION['shop'] = $_REQUEST['shop']; 
																	 
	$url = "https://smsappstore.myshopify.com/admin/webhooks.json";
	$topics = array(
			'customers/create' => 'https://smscountry.herokuapp.com/notify.php?action=customer_signup',
			'orders/create' => 'https://smscountry.herokuapp.com/notify.php?action=order_created',
			'orders/updated' => 'https://smscountry.herokuapp.com/notify.php?action=order_updated',
		);
	foreach($topics as $topic => $address){
		echo "<pre>";
		print_r($topics);
		die;
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
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data))                                                                       
		);
		curl_exec($ch);
	}
	$install_url = shopify\install_url($_REQUEST['shop'], SHOPIFY_APP_API_KEY);
	echo "<script>window.location.href='{$install_url}';</script>";
	exit();
?>
