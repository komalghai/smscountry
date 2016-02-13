<?php 
global $db;
if(!function_exists('saveMessage')){
	function saveMessage($message, $recipient_name, $recipient_number, $message_type, $status="delivered"){
		/* $response = pg_query($db, "INSERT INTO messages values()"); */
		return true;
	}
}
