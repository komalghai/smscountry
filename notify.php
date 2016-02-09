<?php 
require('conf.php');
if(!session_id()) session_start();
global $db, $argv;
date_default_timezone_set('Asia/Kolkata');
$now = date('Y-m-d H:i:s');
$data = "Updated: {$now}";

$webhook = fopen('php://input' , 'rb'); 
while (!feof($webhook)){ 
	$data .= fread($webhook, 4096); 
} 
fclose($webhook); 
/*$data .= '=====================REQUEST START ============================\n';
$data .= print_r($_REQUEST, true);
$data .= '=====================REQUEST END ============================\n\n';
$data .= '=====================SERVER START ============================\n';
$data .= print_r($_SERVER, true);
$data .= '=====================SERVER END ============================\n\n';
$data .= '=====================SESSION START ============================\n';
$data .= print_r($_SESSION, true);
$data .= '=====================SESSION END ============================\n';
$data .= '=====================COOKIE START ============================\n';
$data .= print_r($_COOKIE, true);
$data .= '=====================COOKIE END ============================\n'; */
/* pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'data'"); */

$result = pg_query($db, "SELECT * FROM debug WHERE key = 'data'");
if(pg_num_rows($result)) 
{
	while($response = pg_fetch_assoc($result)) {
		$response = $response['value'];
		$response = str_replace('Updated: 2016-02-08 19:00:34', '', $response);
		echo "<pre>";
		print_R(json_decode($response));
		echo "</pre>";
	}
}
exit('Query executed!');
?>
