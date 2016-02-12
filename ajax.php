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
				$config['SMSHTML'][$key] = $value;
				$config = serialize($config);
				if(pg_query($db, "UPDATE configuration SET data = '{$config}' WHERE store = '{$store}'")){
					exit('1');
				} else {
					exit('0');
				}
			}
			break;
			
		default:
			break;
			
	}
} else {
	exit("ERR: NO ACTION DEFINED!");
}
exit();
