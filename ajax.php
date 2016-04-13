<?php 
require('conf.php');
global $db;
$action = $_REQUEST['action'];
if(!empty($action)){
	switch($action){
		case 'saveSMS':
			$key = $_REQUEST['key']; 
			$value = $_REQUEST['value'];
			$active = $_REQUEST['active'];
			$store = $_REQUEST['store'];
			if(!empty($key) && !empty($value)){
				$config = pg_query($db, "SELECT data FROM configuration WHERE store = '{$store}'");
				$config = pg_fetch_assoc($config);
				$config = unserialize($config['data']);
				$config['SMSHTML'][$key] = $value;
				$config['smsactive'][$key] = $active;
				$config = serialize($config);
				$updated = pg_query($db, "UPDATE configuration SET data = '{$config}' WHERE store = '{$store}'");
				if($updated){
					exit('1');
				} else {
					exit('0');
				}
			}
			break;
		case 'sms_admin_phone':
			$sms_admin_phone = $_REQUEST['sms_admin_phone']; 
			
			$store = $_REQUEST['store'];
			if(!empty($sms_admin_phone)){
				$config = pg_query($db, "SELECT data FROM configuration WHERE store = '{$store}'");
				$config = pg_fetch_assoc($config);
				$config = unserialize($config['data']);
				$config['sms_admin_phone'] = $sms_admin_phone;
				$config = serialize($config);
				$updated = pg_query($db, "UPDATE configuration SET data = '{$config}' WHERE store = '{$store}'");
				if($updated){
					exit('1');
				} else {
					exit('0');
				}
			}
			break;

		case 'sendTestSMS':
			$shop = $_REQUEST['shop'];
			$domain = $_REQUEST['domain'];
			$message = $_REQUEST['message'];
			$mobilenumber = $_REQUEST['mobilenumber'];
			$customVariables = array(
					'[shop_name]' => $shop,
					'[shop_domain]' => $domain,
					'[customer_count]' => '36',
					'[customer_firstname]' => 'John',
					'[customer_lastname]' => 'Doe',
					'[verification_code]' => '5623321',
					'[customer_address]' => '1444 S. Alameda Street',
					'[customer_postcode]' => '90021',
					'[customer_city]' => 'Los Angeles',
					'[customer_country]' => 'United States',
					'[order_id]' => '10045',
					'[order_total]' => '$982.00',
					'[order_products_count]' => '3',
					'[order_status]' => 'Pending',
					'[order_old_status]' => 'Pending',
					'[order_new_status]' => 'Fullfilled',
					'[order_date]' => '26th Jan, 2016',
					'[return_product_name]' => 'Nokia v.2',
					'[return_reason]' => 'The seal of package was broken, looks like it was used before.',
					'[customer_name]' => 'John Doe',
					'[customer_email]' => 'johndoe342@mail.com',
					'[customer_message]' => 'Hi, This message is regarding testing.',
				);
			foreach($customVariables as $find => $replace){
				$message = str_replace($find, $replace, $message);
			}
			//$message = urlencode($message);
			sendTestMessage($message, $mobilenumber);
			break;
			
		case 'savesmscontrydetail':
			$sms_username = $_REQUEST['sms_username'];
			$sms_password = $_REQUEST['sms_password'];
			$sender_id = $_REQUEST['sender_id'];
			$store = $_REQUEST['store'];
			 $lastRow = pg_query($db, "SELECT id FROM smscountrydetail ORDER by id DESC limit 1");
			$lastID = pg_fetch_assoc($lastRow);
			$lastID = (pg_num_rows($lastRow) > 0) ? $lastID['id'] : 0;
			$lastID = $lastID + 1; 
			echo "INSERT INTO smscountrydetail (id, sms_username, sms_password, sender_id) VALUES ('1','{$sms_username}', '{$sms_password}', '{$sender_id}')";
			$storeexist = pg_query($db, "SELECT store FROM smscountrydetail  limit 1");
			$storeexist = pg_fetch_assoc($storeexist);
			if((pg_num_rows($lastRow) > 0)){
				$updated1 = pg_query($db, "UPDATE smscountrydetail SET sms_username = '{$sms_username}' ,sms_password='{$sms_password}' , sender_id='{$sender_id}',store='{$store}'  WHERE store = '{$store}'");	
			}
				else {
					echo $inserted = pg_query($db, "INSERT INTO smscountrydetail (id, sms_username, sms_password, sender_id,store) VALUES ('{$lastID}','{$sms_username}', '{$sms_password}', '{$sender_id}','{$store}')");
				}
				if($inserted  or $updated1){
					exit('1');
				} else {
					exit('0');
				}
			break;
			case 'pagination2':
			 /* if(isset($_REQUEST['actionfunction']) && $_REQUEST['actionfunction']!=''){
			$actionfunction = $_REQUEST['actionfunction'];
  
			call_user_func($actionfunction,$_REQUEST,10,2);
			}
	function showData($data,$limit,$adjacent){ */
		$limit=10;$adjacent=3;
	  $page = $_REQUEST['page'];
	   if($page==1){
	   $start = 0;  
	  }
	  else{
	  $start = ($page-1)*$limit;
	  }
	  echo "testing"; 
	   $historyData=pg_query($db, "SELECT * FROM messages ORDER BY id DESC");
	  echo $rows = pg_num_rows($historyData);
	 echo  $historyData=pg_query($db, "SELECT * FROM messages ORDER BY id DESC limit '{$start}','{$limit}'"); ?>
			<table class='table table-bordered'>
							<thead>
								<tr>
									<th>ID</th>
									<th>Message</th>
									<th>Recipient Name</th>
									<th>Recipient #</th>
									<th>Sent On</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody class='msgdata'>
								<?php  $i=0; while($history = pg_fetch_assoc($historyData)){ $i++; ?>
									
										<td><?php echo $i; ?></td>
										<td class="col-xs-5"><?php echo $history['message_text']; ?></td>
										<td><?php echo $history['recipient_name']; ?></td>
										<td><?php echo $history['recipient_number']; ?></td>
										<td><?php echo date('F j, Y@g:i a', strtotime($history['created_at'])); ?></td>
										<td><?php echo $history['status']; ?></td>
									</tr>
								<?php } ?>
							</tbody>
   

<?php pagination($limit,$adjacent,$rows,$page);  

function pagination($limit,$adjacents,$rows,$page){	
	$pagination='';
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$prev_='';
	$first='';
	$lastpage = ceil($rows/$limit);	
	$next_='';
	$last='';
	if($lastpage > 1)
	{	
		
		//previous button
		if ($page > 1) 
			$prev_.= "<a class='page-numbers' href=\"?page=$prev\">previous</a>";
		else{
			//$pagination.= "<span class=\"disabled\">previous</span>";	
			}
		
		//pages	
		if ($lastpage < 5 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
		$first='';
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";					
			}
			$last='';
		}
		elseif($lastpage > 3 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			$first='';
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";					
				}
			$last.= "<a class='page-numbers' href=\"?page=$lastpage\">Last</a>";			
			}
			
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
		       $first.= "<a class='page-numbers' href=\"?page=1\">First</a>";	
			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";					
				}
				$last.= "<a class='page-numbers' href=\"?page=$lastpage\">Last</a>";			
			}
			//close to end; only hide early pages
			else
			{
			    $first.= "<a class='page-numbers' href=\"?page=1\">First</a>";	
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a class='page-numbers' href=\"?page=$counter\">$counter</a>";					
				}
				$last='';
			}
            
			}
		if ($page < $counter - 1) 
			$next_.= "<a class='page-numbers' href=\"?page=$next\">next</a>";
		else{
			//$pagination.= "<span class=\"disabled\">next</span>";
			}
		$pagination = "<div class=\"pagination\">".$first.$prev_.$pagination.$next_.$last;
		//next button
		
		$pagination.= "</div>\n";		
	}

	echo $pagination;  
}
			break;
		
			case 'Searchhistory':
			$phone = $_REQUEST['phone'];
			$status = $_REQUEST['status'];
			if($phone!="" && $status!=""){
				$config = pg_query($db, "SELECT * FROM messages where recipient_number='{$phone}' and status='{$status}'"); 
			}
			else if($phone!="" && $status==""){
					$config = pg_query($db, "SELECT * FROM messages where recipient_number='{$phone}'"); 
			}
			else if($phone=="" && $status!=""){
					$config = pg_query($db, "SELECT * FROM messages where status='{$status}'"); 
			}
			else {
				$config = pg_query($db, "SELECT * FROM messages"); 
			}
			 $i=0; while($history = pg_fetch_assoc($config)){ $i++; ?>
									<tr>
										<td><?php echo $i; ?></td>
										<td class="col-xs-5"><?php echo $history['message_text']; ?></td>
										<td><?php echo $history['recipient_name']; ?></td>
										<td><?php echo $history['recipient_number']; ?></td>
										<td><?php echo date('F j, Y@g:i a', strtotime($history['created_at'])); ?></td>
										<td><?php echo $history['status']; ?></td>
									</tr>
								<?php } ?>
			<?php 
			break;
		default:
			break;
	}
} else {
	exit("ERR: NO ACTION DEFINED!");
}
exit();
