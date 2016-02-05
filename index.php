<?

    session_start();

    require __DIR__.'/vendor/autoload.php';
    use phpish\shopify;

    require __DIR__.'/conf.php';

  $oauth_token = shopify\access_token($_GET['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_GET['code']);
	$_SESSION['oauth_token'] = $oauth_token;
		$_SESSION['shop'] = $_GET['shop']; 
		echo "hello";?>
		<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script>
  
$.ajax({
	type: 'GET',
	url: "https:\/\/smsappstore.myshopify.com\/admin\/webhooks.json?access_token=<?php echo $_SESSION['oauth_token'] ?>",  
	dataType:'json',
     data: {
  "webhook": {
    "topic": "orders\/create",
    "address": "https:\/\/smscountry.herokuapp.com\/",
    "format": "json"
  }
},
success: function(response){
  alert('yes');
}
});
  
</script>
<?php 
  /*   $shopify = shopify\client($_GET['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, true);

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
