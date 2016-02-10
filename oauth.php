<?php
	session_start();
	require __DIR__.'/vendor/autoload.php';
	use phpish\shopify;
	require __DIR__.'/conf.php';
	/* echo "<pre>"; print_r($_REQUEST); die();
        echo SHOPIFY_APP_SHARED_SECRET;
	# Guard: http://docs.shopify.com/api/authentication/oauth#verification
	 //print_r(shopify\is_valid_request($_GET, SHOPIFY_APP_SHARED_SECRET)) ;*/
       shopify\is_valid_request($_GET, SHOPIFY_APP_SHARED_SECRET) or die('Invalid Request! Request or redirect did not come from Shopify');
       // echo '<script>alert(51);</script>';
	# Step 2: http://docs.shopify.com/api/authentication/oauth#asking-for-permission
	if (!isset($_GET['code']))
	{
		$permission_url = shopify\authorization_url($_GET['shop'], SHOPIFY_APP_API_KEY, array('read_content', 'write_content', 'read_themes', 'write_themes', 'read_products', 'write_products', 'read_customers', 'write_customers', 'read_orders', 'write_orders', 'read_script_tags', 'write_script_tags', 'read_fulfillments', 'write_fulfillments', 'read_shipping', 'write_shipping'),'https://smscountry.herokuapp.com/');
		die("<script>window.location='{$permission_url}';</script>");
	}
	# Step 3: http://docs.shopify.com/api/authentication/oauth#confirming-installation
	try
	{
		# shopify\access_token can throw an exception
		$oauth_token = shopify\access_token($_GET['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_GET['code']);
		$_SESSION['oauth_token'] = $oauth_token;
		$_SESSION['shop'] = $_GET['shop'];
		$access_token = shopify\access_token($_REQUEST['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
		$url = "https://smsappstore.myshopify.com/admin/webhooks.json";
		$topics = array(
				'customers/create' => 'https://smscountry.herokuapp.com/notify.php?action=customer_signup',
				'orders/create' => 'https://smscountry.herokuapp.com/notify.php?action=order_created',
				'orders/updated' => 'https://smscountry.herokuapp.com/notify.php?action=order_updated',
			);
		foreach($topics as $topic => $address){
			$data = array(
					'access_token' => $access_token,
					'webhook' => array(
						'address' => $address,
						'format' => 'json',
						'topic' => $topic,
					)
				);
			$data = json_encode($data);	
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($data))                                                                       
			);
			curl_exec($ch);
		}
		echo "<script>window.location = 'https://smsappstore.myshopify.com/admin/apps/smscountry?conf=200';</script>";
		exit();
	}
	catch (shopify\ApiException $e)
	{
		# HTTP status code was >= 400 or response contained the key 'errors'
		echo $e;
		print_R($e->getRequest());
		print_R($e->getResponse());
	}
	catch (shopify\CurlException $e)
	{
		# cURL error
		echo $e;
		print_R($e->getRequest());
		print_R($e->getResponse());
	}
?>
