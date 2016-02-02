<?php
	# install.php?shop=example-shop.myshopify.com
	if(!session_id()) session_start(); 
	require __DIR__.'/vendor/autoload.php';
	use phpish\shopify;
	require __DIR__.'/conf.php';
	isset($_GET['shop']) or die ('Query parameter "shop" missing.');
	preg_match('/^[a-zA-Z0-9\-]+.myshopify.com$/', $_GET['shop']) or die('Invalid myshopify.com store URL.');
	$install_url = shopify\install_url($_GET['shop'], SHOPIFY_APP_API_KEY);
	$oauth_token = shopify\access_token($_GET['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_GET['code']);
	$_SESSION['oauth_token'] = $oauth_token;
	$_SESSION['shop'] = $_GET['shop'];
	echo "<script>window.location.href='{$install_url}';</script>";
	exit();
?>
