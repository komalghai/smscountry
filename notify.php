<?php 
require('conf.php');
if(!session_id()) session_start();
global $db;
date_set_timezone_default('Asia/Kolkata');
$now = date('Y-m-d H:i:s');
$data = "Updated: {$now}";
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
pg_query($db, "UPDATE debug SET value = '{$data}' WHERE key = 'data'");

$result = pg_query($db, "SELECT * FROM debug WHERE key = 'data'");
if(pg_num_rows($result)) 
{
   while($response = pg_fetch_assoc($result)) {
		echo "<pre>";
		print_R($response);
		echo "</pre>";
	}
}
exit('Query executed!');
?>
