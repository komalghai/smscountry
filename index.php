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
return registerShopifyAppUninstallWebhook('smsappstore.myshopify.com', $oauth_token);
die;
echo "<script>window.location = 'https://smsappstore.myshopify.com/admin/apps';</script>";
exit();
	
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
$baseurl = "https://".SHOPIFY_API_KEY.":".$password."@".$shop_domain."/";;

$url = $baseurl.ltrim($path, '/');
$query = in_array($method, array('GET','DELETE')) ? $params : array();
$payload = in_array($method, array('POST','PUT')) ? stripslashes(json_encode($params)) : array();
$request_headers = in_array($method, array('POST','PUT')) ? array("Content-Type: application/json; charset=utf-8", 'Expect:') : array();
$request_headers[] = 'X-Shopify-Access-Token: ' . $oauth_token;
			list($response_body, $response_headers) = $this->Curl->HttpRequest($method, $url, $query, $payload, $request_headers);
$this->last_response_headers = $response_headers;
$response = json_decode($response_body, true);

if (isset($response['errors']) or ($this->last_response_headers['http_status_code'] >= 400)){
	$body = $response['errors'];
} else {
	$body = $response_body;
}
/*Debug the output in a text_file*/
/* $destination = realpath('../../app/webroot/execution_log') . '/';
$fh = fopen($destination."shopify_app.txt",'a') or die("can't open file");
date_default_timezone_set('GMT');
fwrite($fh, "\n\nDATE: ".date("Y-m-d H:i:s")."\n".$body);
fclose($fh); */
/*Debug Code Ends*/
echo "<pre>";
print_r($body);
die;
?>
