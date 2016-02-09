<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(isset($_REQUEST['code']) && isset($_REQUEST['shop']) && !empty($_REQUEST['code'])){
	require __DIR__.'/conf.php';
	$url = "https://smsappstore.myshopify.com/admin/webhooks.json";
	$topics = array(
			'customers/create' => 'https://smscountry.herokuapp.com/notify.php?action=customer_signup',
			'orders/create' => 'https://smscountry.herokuapp.com/notify.php?action=order_created',
			'orders/updated' => 'https://smscountry.herokuapp.com/notify.php?action=order_updated',
		);
	foreach($topics as $topic => $address){
		$data = array(
				'webhook' => array(
					'address' => $address,
					'format' => 'json',
					'topic' => $topic,
				)
			);
		echo "<pre>";
		print_r($data);
		die;
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
