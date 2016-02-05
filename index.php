<?php

    session_start();

    require __DIR__.'/vendor/autoload.php';
    use phpish\shopify;

    require __DIR__.'/conf.php';

  $oauth_token = shopify\access_token($_GET['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_GET['code']);
	$_SESSION['oauth_token'] = $oauth_token;
		$_SESSION['shop'] = $_GET['shop']; 
		echo "hello";
		$path = "/admin/webhooks.json";
$url = "https://smsappstore.myshopify.com/admin/webhooks.json";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                     
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	'Content-Type: application/json',                                                                                
	'X-Shopify-Access-Token: '.$oauth_token,
);
$response = curl_exec($ch)

echo "<pre>";
print_r($response);
	/*	<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script>
  
$.ajax({
	type: 'GET',
	url: "https:\/\/smsappstore.myshopify.com\/admin\/webhooks.json",  
	dataType:'json',
success: function(response){
  console.log(response);
} 
});
  
</script> */?>
