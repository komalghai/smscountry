<?php
$user="shopifyplugin";
$password="Shopify4plug"; 
$mobilenumbers="919816311185"; 
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
exit('199');
?>
