<?php 
require('conf.php');
if(!session_id()) session_start();
global $db;
date_default_timezone_set('Asia/Kolkata');
$now = date('Y-m-d H:i:s');
$updated = "Updated: {$now}";
pg_query($db, "UPDATE debug SET value = '{$updated}' WHERE key = 'updated'");
$action = $_REQUEST['action'];
if(!empty($action)){
	$data = '';
	$webhook = fopen('php://input' , 'rb'); 
	while(!feof($webhook)){
		$data .= fread($webhook, 4096); 
	} 
	fclose($webhook);
	switch($action){
		case 'customer_signup':
			pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'customer_signup'");
			break;
		case 'order_created':
			pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'order_placed'");
			break;
		case 'order_updated':
			pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'order_status_changed'");
			break;
		default:
			break;
	}
}

/* $result = pg_query($db, "SELECT * FROM debug WHERE key = 'data'");
if(pg_num_rows($result)) 
{
	while($response = pg_fetch_assoc($result)) {
		$response = $response['value'];
		$response = str_replace('Updated: 2016-02-08 19:00:34', '', $response);
		echo "{$response['key']}:: <pre>";
		print_R(json_decode($response));
		echo "</pre>";
	}
} */
exit('Query executed!');
?>
