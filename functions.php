<?php 
echo "sms user name".SMS_USERNAME;
if(!function_exists('saveMessage')){
	function saveMessage($message, $recipient_name, $recipient_number, $message_type, $status="delivered"){
		global $db;
		$lastRow = pg_query($db, "SELECT id FROM messages ORDER by id DESC limit 1");
		$lastID = pg_fetch_assoc($lastRow);
		$lastID = (pg_num_rows($lastRow) > 0) ? $lastID['id'] : 0;
		$lastID = $lastID + 1;
		$character_count = strlen($message);
		$created_at = date('Y-m-d H:i:s');
		pg_query($db, "INSERT INTO messages (id, message_text, recipient_name, recipient_number, type, character_count, created_at, status) VALUES ('{$lastID}', '{$message}', '{$recipient_name}', '{$recipient_number}', '{$message_type}', '{$character_count}', '{$created_at}', '{$status}')");
	}
}

if(!function_exists('sendMessage')){
	function sendMessage($message, $mobilenumber, $recipient_name, $type){
		$url = "http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
		$user = SMS_USERNAME1;
		$password = SMS_PASSWORD1;
		$senderid = SENDER_ID1;
		$messagetype = MESSAGE_TYPE;
		$DReports = DELIVERY_REPORT;
		$ch = curl_init();
		$ret = curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, "User={$user}&passwd={$password}&mobilenumber={$mobilenumber}&message={$message}&sid={$senderid}&mtype={$messagetype}&DR={$DReports}");
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$curlresponse = curl_exec($ch);
		if (empty($ret)) {
			die(curl_error($ch));
		} else {
			$status = 'pending';
			if(strpos($curlresponse, 'OK') !== false){
				$status = 'delivered';
			}
			
			saveMessage($message, $recipient_name, $mobilenumber, $type, $status);
		}
		curl_close($ch);
	}
}

if(!function_exists('sendTestMessage')){
	function sendTestMessage($message, $mobilenumber){
		$url = "http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
		$user = SMS_USERNAME1;
		$password = SMS_PASSWORD1;
		$senderid = SENDER_ID1;
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
			$status = 'pending';
			if(strpos($curlresponse, 'OK') !== false){
				$status = 'delivered';
			}
			
			$status = "http://www.smscountry.com/SMSCwebservice_Bulk.aspx?User={$user}&passwd={$password}&mobilenumber={$mobilenumber}&message={$message}&sid={$senderid}&mtype={$messagetype}&DR={$DReports}";
			$status="username=".SMS_USERNAME1;
			saveMessage($message, $recipient_name, $mobilenumber, $type, $status);
			$info = curl_getinfo($ch);
			curl_close($ch);
		}
	}
}
