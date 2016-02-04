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
	
$shop_domain = 'smsappstore.myshopify.com';
$method = "POST";
$path = "/admin/webhooks.json";
$params = array(
		"webhook" => array( 
			"topic"=>"app/uninstalled",
			"address"=> SITE_URL."notify.php",
			"format"=> "json"
		)
	);

$password = md5(SHOPIFY_SHARED_SECRET.$oauth_token);
$baseurl = "https://".SHOPIFY_API_KEY.":".$password."@".$shop_domain."/";

$url = $baseurl.ltrim($path, '/');
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	'Content-Type: application/json',                                                                                
	'Content-Length: ' . strlen(json_encode($params)),
	'X-Shopify-Access-Token: '.$oauth_token,
);
$response = curl_exec($ch)

echo "<pre>";
print_r($response);
die;
echo "<script>window.location = 'https://smsappstore.myshopify.com/admin/apps';</script>";
exit();
?>
