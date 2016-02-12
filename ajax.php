<?php 
require('conf.php');
global $db;
$action = $_REQUEST['action'];
if(!empty($action)){
	switch($action){
		case 'saveSMS':
			$key = $_REQUEST['key'];
			$value = $_REQUEST['value'];
			$store = $_REQUEST['store'];
			if(!empty($key) && !empty($value)){
				$config = pg_query($db, "SELECT data FROM configuration WHERE store = '{$store}'");
				$config = pg_fetch_assoc($config);
				$config = unserialize($config['data']);
				echo "<pre>";
				print_R($config);
				exit();
			}
			break;
			
		default:
			break;
			
	}
} else {
	exit("ERR: NO ACTION DEFINED!");
}
exit();
