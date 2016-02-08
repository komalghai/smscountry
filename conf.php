<?php 
define('SHOPIFY_APP_API_KEY', '7662e5daf152854818a5066e4d5e61d6'); 
define('SHOPIFY_APP_SHARED_SECRET', '49ab4645e77da32cd24c39ae49b81a59-1454926205');

$host = "host=ec2-54-197-247-170.compute-1.amazonaws.com"; 
$port = "port=5432"; 
$dbname = "dbname=daovncsi96sin3"; 
$credentials = "user=vrjlpasdpeblfz password=N3mICKo5DkmsMjntro7bSPoQQA"; 
$db = pg_connect( "$host $port $dbname $credentials" ); 
if(!$db){ 
	echo "Error : Unable to open database\n"; 
} 
?>
