<?php 
	require __DIR__.'/vendor/autoload.php';
	use phpish\shopify;
	require __DIR__.'/conf.php';
	isset($_REQUEST['shop']) or die ('Query parameter "shop" missing.');
	preg_match('/^[a-zA-Z0-9\-]+.myshopify.com$/', $_REQUEST['shop']) or die('Invalid myshopify.com store URL.');
echo 	$install_url = shopify\install_url($_REQUEST['shop'], SHOPIFY_APP_API_KEY);
	/*echo "<script>window.location='{$install_url}';</script>";*/
	exit();
?>
