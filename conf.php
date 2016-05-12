<?php 
/** variables **/
$GLOBALS['base_url'] = 'https://'.$_SERVER['HTTP_HOST'].'/';
$GLOBALS['ajax_url'] = 'https://'.$_SERVER['HTTP_HOST'].'/ajax.php';

/** shopify credentials **/
define('SHOPIFY_APP_API_KEY', '2f32ee6aa7b785c18281b4cf8fc26346'); 
define('SHOPIFY_APP_SHARED_SECRET', '8e7e97c6b762aed851b3387f5a249844');



/** DB connectivity **/
$host = "host=ec2-54-197-247-170.compute-1.amazonaws.com"; 
$port = "port=5432"; 
$dbname = "dbname=daovncsi96sin3"; 
$credentials = "user=vrjlpasdpeblfz password=N3mICKo5DkmsMjntro7bSPoQQA"; 
$db = pg_connect( "$host $port $dbname $credentials" ); 
if(!$db){ 
	echo "Error : Unable to open database\n"; 
}
$store=$_REQUEST['shop'];
$config1= pg_query($db, "SELECT * FROM smscountrydetail where store='{$store}'");
$config1 = pg_fetch_assoc($config1);
$sms_username1=$config1['sms_username'];
$sms_password1=$config1['sms_password'];
 $sender_id1=$config1['sender_id'];
/** SMS country setup **/

define('SMS_USERNAME1', $sms_username1);
define('SMS_PASSWORD1', $sms_password1);
define('SENDER_ID1', $sender_id1);
define('MESSAGE_TYPE', 'N');
define('DELIVERY_REPORT', 'Y');
require('functions.php');

