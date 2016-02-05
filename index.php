<?php

    session_start();

    require __DIR__.'/vendor/autoload.php';
    use phpish\shopify;

    require __DIR__.'/conf.php';

  $oauth_token = shopify\access_token($_GET['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_GET['code']);
	$_SESSION['oauth_token'] = $oauth_token;
		$_SESSION['shop'] = $_GET['shop']; 
		echo "hello";
$url = "https://smsappstore.myshopify.com/admin/webhooks.json";
$query="access_token=$_SESSION['oauth_token']";
/* $ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                     
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	'Content-Type: application/json',                                                                                
	'X-Shopify-Access-Token: '.$oauth_token,
);
$response = curl_exec($ch) */
 $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $return = curl_exec ($ch);
    curl_close ($ch);

    echo $return;

  /* 
echo "<pre>";
print_r($response);
  $shopify = shopify\client($_GET['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, true);

    try
    {
        # Making an API request can throw an exception
        $customers = $shopify('POST /admin/webhooks.json?access_token='.<?php echo $_SESSION['oauth_token'] ?>, array(), array
        (
            'webook' => array 
            (
                "topic": "customers/create",
                "address": "https://smscountry.herokuapp.com/",
                "format": "json"
            )

        ));

        print_r($customers);
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
 */
?>
