<?php 
require('conf.php');
global $db;
$now = date('U-m-d H:i:s');
$data = "updated {$now}";
$data .= '=====================REQUEST START ============================';
$data .= print_r($_REQUEST, true);
$data .= '=====================REQUEST END ============================';
$data .= '=====================SERVER START ============================';
$data .= print_r($_SERVER, true);
$data .= '=====================SERVER END ============================';
$data .= '=====================SESSION START ============================';
$data .= print_r($_SESSION, true);
$data .= '=====================SESSION END ============================';
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

<?php
/* $user="shopifyplugin";
$password="Shopify4plug"; 
$mobilenumbers="91814610567"; 
$message = "test messgae";
$senderid="SMSCNTRY";
$messagetype="N";
$DReports="Y";
$url="http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
$message = urlencode($message);
$ch = curl_init();
if (!$ch){
	die("Couldn't initialize a cURL handle");
}
$ret = curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt ($ch, CURLOPT_POSTFIELDS,
"User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$curlresponse = curl_exec($ch);
if(curl_errno($ch))
	echo 'curl error : '. curl_error($ch);
if (empty($ret)) {
	die(curl_error($ch));
	curl_close($ch);
} else {
	$info = curl_getinfo($ch);
	curl_close($ch);
	echo $curlresponse;
	echo "Message Sent Succesfully" ;
}
exit('199'); */
?>
