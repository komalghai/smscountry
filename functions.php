<?php 
if(!function_exists('generateWebhook')){
	function generateWebhook($url, $access_token, $address, $topic, $format = 'json', $method = 'POST'){
		$data = array(
				'access_token' => $access_token,
				'webhook' => array(
					'address' => $address,
					'format' => $format,
					'topic' => $topic,
				)
			);
		$data = json_encode($data);	
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data))                                                                       
		);
		curl_exec($ch);
	}
}
?>
