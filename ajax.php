<?php 
require('conf.php');
global $db;
$action = $_REQUEST['action'];
if(!empty($action)){
	switch($action){
		case 'saveSMS':
			$key = $_REQUEST['key']; 
			$value = $_REQUEST['value'];
			$store = $_REQUEST['store'];
			if(!empty($key) && !empty($value)){
				$config = pg_query($db, "SELECT data FROM configuration WHERE store = '{$store}'");
				$config = pg_fetch_assoc($config);
				$config = unserialize($config['data']);
				$config['SMSHTML'][$key] = $value;
				$config = serialize($config);
				if(pg_query($db, "UPDATE configuration SET data = '{$config}' WHERE store = '{$store}'")){
					exit('1');
				} else {
					exit('0');
				}
			}
			break;

		case 'sendTestSMS':
			$store = $_REQUEST['store'];
			$req = curl_init();
			curl_setopt($req,CURLOPT_URL, "https://{$store}/admin/shop.json");
			curl_setopt($req,CURLOPT_RETURNTRANSFER,true);
			$storeData=curl_exec($req);
			curl_close($req);
			echo "<pre>";
			print_R(json_decode($storeData));
			exit();
			return;
			$user = SMS_USERNAME;
			$password = SMS_PASSWORD; 
			$mobilenumbers = "919XXXXXXXXX"; 
			$message = "test messgae";
			$senderid = SENDER_ID;
			$messagetype = MESSAGE_TYPE;
			$DReports = DELIVERY_REPORT;
			$url = "http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
			$message = urlencode($message);
			$ch = curl_init();
			if (!$ch){
				die("Couldn't initialize a cURL handle");
			}
			$ret = curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, "User={$user}&passwd={$password}&mobilenumber={$mobilenumbers}&message={$message}&sid={$senderid}&mtype={$messagetype}&DR={$DReports}");
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
				//echo "Message Sent Succesfully" ;
			}
			break;
			
		default:
			break;
			
	}
} else {
	exit("ERR: NO ACTION DEFINED!");
}
exit();
