<?php session_start(); 
require __DIR__.'/conf.php';
echo SHOPIFY_APP_API_KEY;
echo SHOPIFY_APP_SHARED_SECRET;
	/*echo  $oauth_token = shopify\access_token($_GET['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_GET['code']);
	$_SESSION['oauth_token'] = $oauth_token;
		$_SESSION['shop'] = $_GET['shop']; */
?>
<?php print_r($_GET); ?>
<?php print_r($_SESSION); ?>
<?php /* echo "https://smsappstore.myshopify.com/admin/webhooks.json?access_token=".$_SESSION['oauth_token']; */?>
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script>
  
$.ajax({
	type: 'POST',
	url: "https://smsappstore.myshopify.com/admin/webhooks.json?access_token=2f32ee6aa7b785c18281b4cf8fc26346",  
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
