<?php 																	 
	$ch = curl_init("https://smsappstore.myshopify.com/admin/webhooks.json");                                              
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                   
	);
	$response = curl_exec($ch);
	echo "<pre>";
	print_r(json_decode($response));
	die;
	echo "<script>window.location = 'https://smsappstore.myshopify.com/admin/apps';</script>";
	exit();
?>
