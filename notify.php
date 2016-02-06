<?php 
require('conf.php');
global $db;

$result = pg_query($db, "SELECT * FROM debug WHERE key = 'data'");
if(pg_num_rows($result)) 
{
   while($response = pg_fetch_assoc($result)) {
		echo "<pre>";
		print_R($response);
		echo "</pre>";
	}
}

exit('1');
date_set_timezone_default('Asia/Kolkata');
$now = date('Y-m-d H:i:s');
$data = "updated {$now}\n\n";
$data .= '=====================REQUEST START ============================\n';
$data .= print_r($_REQUEST, true);
$data .= '=====================REQUEST END ============================\n\n';
$data .= '=====================SERVER START ============================\n';
$data .= print_r($_SERVER, true);
$data .= '=====================SERVER END ============================\n\n';
$data .= '=====================SESSION START ============================\n';
$data .= print_r($_SESSION, true);
$data .= '=====================SESSION END ============================\n';
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
