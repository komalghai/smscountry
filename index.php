<?php 
if(isset($_REQUEST['code']) && isset($_REQUEST['shop']) && !empty($_REQUEST['code'])){
	echo "<script>window.location = 'https://smsappstore.myshopify.com/admin/apps';</script>";
	exit();
}
?>
