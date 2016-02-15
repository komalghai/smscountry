<?php 
global $db;

if(!function_exists('saveMessage')){
	function saveMessage($message, $recipient_name, $recipient_number, $message_type, $status="delivered"){
		$character_count = strlen($message);
		$created_at = date('Y-m-d H:i:s');
		return pg_query($db, "INSERT INTO messages (message_text, recipient_name, recipient_number, type, character_count, created_at, status) VALUES ('{$message}', '{$recipient_name}', '{$recipient_number}', '{$message_type}', '{$character_count}', '{$created_at}', '{$status}')");
	}
}

if(!function_exists('sendMessage')){
	function sendMessage($message, $mobilenumber, $recipient_name, $type){
		$url = "http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
		$user = SMS_USERNAME;
		$password = SMS_PASSWORD;
		$senderid = SENDER_ID;
		$messagetype = MESSAGE_TYPE;
		$DReports = DELIVERY_REPORT;
		$ch = curl_init();
		if (!$ch){
			die("Couldn't initialize a cURL handle");
		}
		$ret = curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, "User={$user}&passwd={$password}&mobilenumber={$mobilenumber}&message={$message}&sid={$senderid}&mtype={$messagetype}&DR={$DReports}");
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$curlresponse = curl_exec($ch);
		if (empty($ret)) {
			die(curl_error($ch));
			curl_close($ch);
		} else {
			$info = curl_getinfo($ch);
			curl_close($ch);
			$status = 'pending';
			if(strpos($curlresponse, 'OK') !== false){
				$status = 'delivered';
			}
			saveMessage(urldecode($message), $recipient_name, $mobilenumber, $type, $status);
			echo $curlresponse;
			exit();
		}
	}
}

if(!function_exists('sendTestMessage')){
	function sendTestMessage($message, $mobilenumber){
		$url = "http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
		$user = SMS_USERNAME;
		$password = SMS_PASSWORD;
		$senderid = SENDER_ID;
		$messagetype = MESSAGE_TYPE;
		$DReports = DELIVERY_REPORT;
		$ch = curl_init();
		if (!$ch){
			die("Couldn't initialize a cURL handle");
		}
		$ret = curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, "User={$user}&passwd={$password}&mobilenumber={$mobilenumber}&message={$message}&sid={$senderid}&mtype={$messagetype}&DR={$DReports}");
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$curlresponse = curl_exec($ch);
		if (empty($ret)) {
			die(curl_error($ch));
			curl_close($ch);
		} else {
			$info = curl_getinfo($ch);
			curl_close($ch);
			echo $curlresponse;
			exit();
		}
	}
}