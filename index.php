<?php 
if(!session_id()) session_start(); 
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
require __DIR__.'/conf.php';
isset($_REQUEST['shop']) or die ('Query parameter "shop" missing.');
preg_match('/^[a-zA-Z0-9\-]+.myshopify.com$/', $_REQUEST['shop']) or die('Invalid myshopify.com store URL.');
$oauth_token = shopify\access_token($_REQUEST['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
$_SESSION['oauth_token'] = $oauth_token;
$_SESSION['shop'] = $_REQUEST['shop'];	 
?>
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script>
/* $.ajax({
	url: "https://smsappstore.myshopify.com/admin/webhooks.json",  
	dataType:'json',
	headers: {"Access-Control-Allow-Origin": "*"},
	data:{
		access_token: '<?php echo $oauth_token; ?>',
	},
	success: function(response){
		alert(response);
	}
}); */

var xhr = new XMLHttpRequest();
xhr.open("GET", "https://smsappstore.myshopify.com/admin/webhooks.json?access_token=<?php echo $oauth_token; ?>", true);
xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
xhr.onload = function () {
    console.log(xhr.responseText);
};
xhr.send();
</script>
