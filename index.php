<?php  session_start();
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
	url: "https://smsappstore.myshopify.com/admin/webhooks.json?access_token=<?php echo $oauth_token; ?>",  
	dataType:'json',
	success: function(response){
	alert('yes');
	}
});
  
</script>
