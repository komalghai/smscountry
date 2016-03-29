<?php 
require('conf.php');
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
global $db;
$store = $_REQUEST['shop'];
$code = $_REQUEST['code'];
$access_token = shopify\access_token($store, SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $code);
$storeData = json_decode(file_get_contents("https://{$store}/admin/shop.json?access_token={$access_token}"));
$config = pg_query($db, "SELECT data FROM configuration WHERE store = '{$store}'");
$config = pg_fetch_assoc($config);
echo '<pre>';print($config); echo'</pre>';
$config = unserialize($config['data']);

$CustomerCustomerSignup = isset($config['SMSHTML']['CustomerCustomerSignup']) ? $config['SMSHTML']['CustomerCustomerSignup'] : null;
$CustomerCustomerSignupVerification = isset($config['SMSHTML']['CustomerCustomerSignupVerification']) ? $config['SMSHTML']['CustomerCustomerSignupVerification'] : null;
$CustomerOrderPlaced = isset($config['SMSHTML']['CustomerOrderPlaced']) ? $config['SMSHTML']['CustomerOrderPlaced'] : null;
$CustomerOrderStatusChanged = isset($config['SMSHTML']['CustomerOrderStatusChanged']) ? $config['SMSHTML']['CustomerOrderStatusChanged'] : null;
$AdminCustomerSignup = isset($config['SMSHTML']['AdminCustomerSignup']) ? $config['SMSHTML']['AdminCustomerSignup'] : null;
$AdminCustomerSignupScheduled = isset($config['SMSHTML']['AdminCustomerSignupScheduled']) ? $config['SMSHTML']['AdminCustomerSignupScheduled'] : null;
$AdminOrderPlaced = isset($config['SMSHTML']['AdminOrderPlaced']) ? $config['SMSHTML']['AdminOrderPlaced'] : null;
$AdminOrderReturnRequest = isset($config['SMSHTML']['AdminOrderReturnRequest']) ? $config['SMSHTML']['AdminOrderReturnRequest'] : null;
$AdminContactInquiry = isset($config['SMSHTML']['AdminContactInquiry']) ? $config['SMSHTML']['AdminContactInquiry'] : null;

$historyData = pg_query($db, "SELECT * FROM messages ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Configuration - SMS Country</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<!--script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script-->
		<script type="text/javascript">
		jQuery(function(){
			if(jQuery(document).find('.sms-config').length){
				jQuery('ul.tabs').find('li a').click(function(e){
					e.preventDefault();
					if(jQuery(this).parent().hasClass('active')){
						return false;
					} else {
						jQuery('div.nav-content').hide(1);
						jQuery('ul.tabs > li').removeClass('active');
						jQuery(this).parent().addClass('active');
						jQuery(jQuery(this).attr('href')).fadeIn();
					}
				});
			}
		});
		
		function sendTestSMS(_type){
			if((_type =='') || (jQuery(document).find('textarea[name="'+_type+'"]').val() == '')) return;
			jQuery('#testSMSLoader').fadeIn();
			jQuery.ajax({
				type: 'post',
				url: '<?php echo $ajax_url; ?>',
				data: {
					action: 'sendTestSMS',
					mobilenumber: '<?php echo $storeData->shop->phone; ?>',
					shop: '<?php echo $storeData->shop->name; ?>',
					domain: '<?php echo $storeData->shop->domain; ?>',
					message: jQuery(document).find('textarea[name="'+_type+'"]').val(),
				},
				success: function(response){
					console.log(response);
					jQuery('#testSMSLoader').fadeOut();
				},
				error: function(response){
					jQuery('#testSMSLoader').fadeOut();
				}
			});
		}
		
		function save(type){
			if(type =='') return;
			return saveSMS(type, jQuery(document).find('textarea[name="'+type+'"]').val());
		}
		function save(type,check){
			if(type =='') return;
			var active=false;
			if(jQuery(document).find('checkbox[name="'+check+'"]').is(":checked"))
			{ active=true; }
			return saveSMS(type, jQuery(document).find('textarea[name="'+type+'"]').val(),active);
		}
		
		function saveAll(){
			return;
		}
		
		function saveSMS(_key, _value){
			if(_key == '') return;
			jQuery('#'+_key+'Loader').fadeIn();
			jQuery.ajax({
				type: 'post',
				url: '<?php echo $ajax_url; ?>',
				data: {
					action: 'saveSMS',
					store: '<?php echo $_REQUEST['shop']; ?>',
					key: _key,
					value: _value,
				},
				success: function(response){
					jQuery('#'+_key+'Loader').fadeOut();
				},
				error: function(response){
					jQuery('#'+_key+'Loader').fadeOut();
				}
			});
		}		
		</script>
		<style type="text/css">
			ul.tabs > li {
				border-bottom: 0.5px solid #ccc;
				border-right: 0.5px solid #ccc;
				min-height: 50px;
				padding: 0;
			}
			ul.tabs > li.active {
				border-bottom: 4px solid #31b0d5;
			}
			ul.tabs > li.active > a {
				min-height: 47px;
			}
			ul.tabs > li > a {
				font-weight: bold;
				min-height: 50px;
				padding-top: 15px;
				text-align: center;
			}
			div.nav-content{
				display: none;
			}
			.text-info{
				background: #479ccf none repeat scroll 0 0 !important;
				color: #479ccf;
			}
			.code{
				background: rgb(221, 221, 221) none repeat scroll 0 0;
				color: rgb(85, 85, 85);
				float: left;
				padding: 10px;
				width: 100%;
			}
			.sms-textarea {
				width: 100%;
				float: left;
				height: 139px;
			}
			.send-test-sms {
				float: right;
				margin: 20px 0;
			} 
			.customer-alerts {
				border-bottom: 2px solid #31b0d5;
				float: left;
				padding: 20px 0;
				position: relative;
				width: 100%;
			}
			.right {
				float: right;
				margin-right: 59px;
				width: 50%;
			}
			.nav-content .col-xs-4 {
				margin-left: 80px;
			}
			.loader-fixed {
				position: fixed !important;
			}
			.loader {
				background: #eee none repeat scroll 0 0;
				clear: both;
				height: 100%;
				left: 0;
				opacity: 0.8;
				position: absolute;
				top: 0;
				width: 100%;
				z-index: 999;
			}
		</style>
	</head>
	<body>
		<div class="col-xs-12 padding-top sms-config">
			<h3 class="alert alert-info">Configuration</h3>
			<form>
				<div class="box container well col-xs-12" style="padding: 0">
					<ul class="tabs nav">
						<li class="col-xs-4 active"><a href="#customer-sms-alerts">Customer SMS Alerts</a></li>
						<li class="col-xs-4"><a href="#admin-sms-alerts">Admin SMS Alerts</a></li>
						<li class="col-xs-4"><a href="#sms-history">SMS History</a></li>
					</ul>
					<div id="customer-sms-alerts" class="nav-content" style="display: block;">
						<div class="customer-signup customer-alerts">
							<div id="CustomerCustomerSignupLoader" style="display: none;" class="loader">
								<div style="margin: 80px 0px 0px 40%;">
									<div style="margin-left: 80px;">Saving</div>
									<img src="https://cdn.shopify.com/s/files/1/1141/9304/files/loader.gif?12050156025147783748" alt="Saving" title="Saving">
								</div>
							</div>
							<div class="col-xs-4">
							<input type="checkbox" name="custsignup"/>
								<h4>New Customer Signup:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
										<p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea" name="CustomerCustomerSignup"><?php echo $CustomerCustomerSignup; ?></textarea>
								</div>
							</div>
							<div class="col-xs-11 text-right">
								<p></p>
								<a href="javascript: void(0);" class="btn btn-info" onclick="return sendTestSMS('CustomerCustomerSignup');">Send Test SMS</a>
								<a class="btn btn-success" href="javascript: void(0);" onclick="return save('CustomerCustomerSignup,custsignup');">Save</a>
								&nbsp;&nbsp;
							</div>
						</div>
						<div class="customer-signup-verification customer-alerts">
							<div id="CustomerCustomerSignupVerificationLoader" style="display: none;" class="loader">
								<div style="margin: 80px 0px 0px 40%;">
									<div style="margin-left: 80px;">Saving</div>
									<img src="https://cdn.shopify.com/s/files/1/1141/9304/files/loader.gif?12050156025147783748" alt="Saving" title="Saving">
								</div>
							</div>
							<div class="col-xs-4">
							<input type="checkbox" name="custsignupverf"/>
								<h4>New Customer Signup verification:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [verification_code].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<p style="margin-top: 30px;"></p>
									<textarea class="sms-textarea" name="CustomerCustomerSignupVerification"><?php echo $CustomerCustomerSignupVerification; ?></textarea>
								</div>
							</div>
							<div class="col-xs-11 text-right">
								<p></p>
								<a href="javascript: void(0);" class="btn btn-info" onclick="return sendTestSMS('CustomerCustomerSignupVerification');">Send Test SMS</a>
								<a class="btn btn-success" href="javascript: void(0);" onclick="return save('CustomerCustomerSignupVerification');">Save</a>
								&nbsp;&nbsp;
							</div>
						</div>
						<div class="order-placed customer-alerts">
							<div id="CustomerOrderPlacedLoader" style="display: none;" class="loader">
								<div style="margin: 80px 0px 0px 40%;">
									<div style="margin-left: 80px;">Saving</div>
									<img src="https://cdn.shopify.com/s/files/1/1141/9304/files/loader.gif?12050156025147783748" alt="Saving" title="Saving">
								</div>
							</div>
							<div class="col-xs-4">
							<input type="checkbox" name="custoderp"/>
								<h4>New Order Placed:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname],[customer_address],[customer_postcode],[customer_city],[customer_country],[order_id],[order_total],[order_products_count],[order_status].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea" name="CustomerOrderPlaced"><?php echo $CustomerOrderPlaced; ?></textarea>
								</div>
							</div>
							<div class="col-xs-11 text-right">
								<p></p>
								<a href="javascript: void(0);" class="btn btn-info" onclick="return sendTestSMS('CustomerOrderPlaced');">Send Test SMS</a>
								<a class="btn btn-success" href="javascript: void(0);" onclick="return save('CustomerOrderPlaced');">Save</a>
								&nbsp;&nbsp;
							</div>
						</div>
						<div class="order-status-changed customer-alerts">
							<div id="CustomerOrderStatusChangedLoader" style="display: none;" class="loader">
								<div style="margin: 80px 0px 0px 40%;">
									<div style="margin-left: 80px;">Saving</div>
									<img src="https://cdn.shopify.com/s/files/1/1141/9304/files/loader.gif?12050156025147783748" alt="Saving" title="Saving">
								</div>
							</div>
							<div class="col-xs-4">
							<input type="checkbox" name="custorderschange"/>
								<h4>Order status changed:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname], [customer_address], [customer_postcode],[customer_city],[customer_country],[order_id],[order_total],[order_products_count],[order_old_status],[order_new_status],[order_date].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									 <p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea" name="CustomerOrderStatusChanged"><?php echo $CustomerOrderStatusChanged; ?></textarea>
								</div>
							</div>
							<div class="col-xs-11 text-right">
								<p></p>
								<a href="javascript: void(0);" class="btn btn-info" onclick="return sendTestSMS('CustomerOrderStatusChanged');">Send Test SMS</a>
								<a class="btn btn-success" href="javascript: void(0);" onclick="return save('CustomerOrderStatusChanged');">Save</a>
								&nbsp;&nbsp;
							</div>
						</div>
					</div>
					<div id="admin-sms-alerts" class="nav-content">
						<div class="admin-customer-signup customer-alerts">
							<h4>New Customer Signup:</h4>
							<div class="col-xs-12">
								<div id="AdminCustomerSignupLoader" style="display: none;" class="loader">
									<div style="margin: 80px 0px 0px 40%;">
										<div style="margin-left: 80px;">Saving</div>
										<img src="https://cdn.shopify.com/s/files/1/1141/9304/files/loader.gif?12050156025147783748" alt="Saving" title="Saving">
									</div>
								</div>
								<div class="col-xs-4">
									<h5>Single Customer Signup:</h5>
									<code class="code">
										You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname].
									</code>
								</div>
								<div class="col-xs-6">
									<div class="form-group">
										<p style="margin-top: 30px;"></p>
										<textarea class="sms-textarea" name="AdminCustomerSignup"><?php echo $AdminCustomerSignup; ?></textarea>
									</div>
								</div>
								<div class="col-xs-11 text-right">
									<p></p>
									<a href="javascript: void(0);" class="btn btn-info" onclick="return sendTestSMS('AdminCustomerSignup');">Send Test SMS</a>
									<a class="btn btn-success" href="javascript: void(0);" onclick="return save('AdminCustomerSignup');">Save</a>
									&nbsp;&nbsp;
								</div>
							</div>
							<div class="col-xs-12">
								<div id="AdminCustomerSignupScheduledLoader" style="display: none;" class="loader">
									<div style="margin: 80px 0px 0px 40%;">
										<div style="margin-left: 80px;">Saving</div>
										<img src="https://cdn.shopify.com/s/files/1/1141/9304/files/loader.gif?12050156025147783748" alt="Saving" title="Saving">
									</div>
								</div>
								<div class="col-xs-4">
									<h5>Daily, weekly or Monthly signups:</h5>
									<code class="code">
										You can use following variables: <br>[shop_name], [shop_domain], [customer_count].
									</code>
									<select class="form-control" name="alert_duration">
										<option value="">--alert duration--</option>
										<option value="daily">Daily</option>
										<option value="weekly">Weekly</option>
										<option value="monthly">Monthly</option>
									</select>
								</div>
								<div class="col-xs-6">
									<div class="form-group">
											<p style="margin-top: 30px;"></p>
										 <textarea class="sms-textarea" name="AdminCustomerSignupScheduled"><?php echo $AdminCustomerSignupScheduled; ?></textarea>
									</div>
								</div>
								<div class="col-xs-11 text-right">
									<p></p>
									<a href="javascript: void(0);" class="btn btn-info" onclick="return sendTestSMS('AdminCustomerSignupScheduled');">Send Test SMS</a>
									<a class="btn btn-success" href="javascript: void(0);" onclick="return save('AdminCustomerSignupScheduled');">Save</a>
									&nbsp;&nbsp;
								</div>
							</div>
						</div>	
						<div class="admin-order-placed customer-alerts">
							<div id="AdminOrderPlacedLoader" style="display: none;" class="loader">
								<div style="margin: 80px 0px 0px 40%;">
									<div style="margin-left: 80px;">Saving</div>
									<img src="https://cdn.shopify.com/s/files/1/1141/9304/files/loader.gif?12050156025147783748" alt="Saving" title="Saving">
								</div>
							</div>
							<div class="col-xs-4">
								<h4>New Order Placed:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname],[customer_address],[customer_postcode],[customer_city],[customer_country],[order_id],[order_total],[order_products_count],[order_status].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea" name="AdminOrderPlaced"><?php echo $AdminOrderPlaced; ?></textarea>
								</div>
							</div>
							<div class="col-xs-11 text-right">
								<p></p>
								<a href="javascript: void(0);" class="btn btn-info" onclick="return sendTestSMS('AdminOrderPlaced');">Send Test SMS</a>
								<a class="btn btn-success" href="javascript: void(0);" onclick="return save('AdminOrderPlaced');">Save</a>
								&nbsp;&nbsp;
							</div>
						</div>
						<div class="admin-order-return-request customer-alerts">
							<div id="AdminOrderReturnRequestLoader" style="display: none;" class="loader">
								<div style="margin: 80px 0px 0px 40%;">
									<div style="margin-left: 80px;">Saving</div>
									<img src="https://cdn.shopify.com/s/files/1/1141/9304/files/loader.gif?12050156025147783748" alt="Saving" title="Saving">
								</div>
							</div>
							<div class="col-xs-4">
								<h4>Order Return Request:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname],[customer_address],[customer_postcode],[customer_city],[customer_country],[order_id],[order_total],[order_status],[return_product_name],[return_reason].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea" name="AdminOrderReturnRequest"><?php echo $AdminOrderReturnRequest; ?></textarea>
								</div>
							</div>
							<div class="col-xs-11 text-right">
								<p></p>
								<a href="javascript: void(0);" class="btn btn-info" onclick="return sendTestSMS('AdminOrderReturnRequest');">Send Test SMS</a>
								<a class="btn btn-success" href="javascript: void(0);" onclick="return save('AdminOrderReturnRequest');">Save</a>
								&nbsp;&nbsp;
							</div>
						</div>
						<div class="admin-contact-inquiry customer-alerts">
							<div id="AdminContactInquiryLoader" style="display: none;" class="loader">
								<div style="margin: 80px 0px 0px 40%;">
									<div style="margin-left: 80px;">Saving</div>
									<img src="https://cdn.shopify.com/s/files/1/1141/9304/files/loader.gif?12050156025147783748" alt="Saving" title="Saving">
								</div>
							</div>
							<div class="col-xs-4">
								<h4>Contact Inquiry:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname],[customer_email],[customer_message].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea" name="AdminContactInquiry"><?php echo $AdminContactInquiry; ?></textarea>
								</div>
							</div>
							<div class="col-xs-11 text-right">
								<p></p>
								<a href="javascript: void(0);" class="btn btn-info" onclick="return sendTestSMS('AdminContactInquiry');">Send Test SMS</a>
								<a class="btn btn-success" href="javascript: void(0);" onclick="return save('AdminContactInquiry');">Save</a>
								&nbsp;&nbsp;
							</div>
						</div>
					</div>
					<div id="sms-history" class="nav-content">
						<table class="table table-bordered">
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
							<tbody>
								<?php $i=0; while($history = pg_fetch_assoc($historyData)){ $i++; ?>
									<tr>
										<td><?php echo $i; ?></td>
										<td class="col-xs-5"><?php echo $history['message_text']; ?></td>
										<td><?php echo $history['recipient_name']; ?></td>
										<td><?php echo $history['recipient_number']; ?></td>
										<td><?php echo date('F j, Y@g:i a', strtotime($history['created_at'])); ?></td>
										<td><?php echo $history['status']; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="col-xs-12 text-right" style="padding: 20px;">
						<p>&nbsp;</p>
						<!--a href="javascript: void(0);" class="btn btn-success" onClick="return saveAll();">Save all configuration</a-->
					</div>					
				</div>
			</form>
			<div style="display: none;" id="testSMSLoader" class="loader loader-fixed">
				<div style="margin: 80px 0px 0px 40%;">
					<div style="margin-left: 80px;">Sending</div>
					<img src="https://cdn.shopify.com/s/files/1/1141/9304/files/loader.gif?12050156025147783748" alt="Saving" title="Saving">
				</div>
			</div>
		</div>
	</body>
</html>
