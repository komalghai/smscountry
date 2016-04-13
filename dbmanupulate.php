<?php
require('conf.php');
global $db;

		$limit=10;$adjacent=3;
	  $page = $_REQUEST['page'];
	   if($page==1){
	   $start = 0;  
	  }
	  else{
	  $start = ($page-1)*$limit;
	  }
	  echo "SELECT * FROM messages ORDER BY id DESC limit '{$start}','{$limit}'"; 
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
?>
