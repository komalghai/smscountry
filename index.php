<?php 
if(isset($_REQUEST['code']) && isset($_REQUEST['shop']) && !empty($_REQUEST['code'])){
	require __DIR__.'/conf.php';
	require __DIR__.'/vendor/autoload.php';
	use phpish\shopify;
	$access_token = shopify\access_token($_REQUEST['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
	$url = "https://smsappstore.myshopify.com/admin/webhooks.json";
	$topics = array(
			'customers/create' => 'https://smscountry.herokuapp.com/notify.php?action=customer_signup',
			'orders/create' => 'https://smscountry.herokuapp.com/notify.php?action=order_created',
			'orders/updated' => 'https://smscountry.herokuapp.com/notify.php?action=order_updated',
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
	echo "<script>window.location = 'https://smsappstore.myshopify.com/admin/apps';</script>";
	exit();
}
?>
