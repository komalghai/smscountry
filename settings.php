<?php require('conf.php'); ?>
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
		
		function sendTestSMS(type){
			if(type =='') return;
			switch(type){
				case 'CustomerCustomerSignup':
					return;
					break;
				
				case 'CustomerCustomerSignupVerification':
					return;
					break;
				
				case 'CustomerOrderPlaced':
					return;
					break;
				
				case 'CustomerOrderStatusChanged':
					return;
					break;
				
				case 'AdminCustomerSignup':
					return;
					break;
				
				case 'AdminCustomerSignupScheduled':
					return;
					break;
				
				case 'AdminOrderPlaced':
					return;
					break;
				
				case 'AdminOrderReturnRequest':
					return;
					break;
				
				case 'AdminContactInquiry':
					return;
					break;
				
				default:
					break;
			}
		}
		
		function save(type){
			if(type =='') return;
			switch(type){
				case 'CustomerCustomerSignup':
					return saveSMS('CustomerCustomerSignup', jQuery(document).find('textarea[name="CustomerCustomerSignup"]').val());
					break;
				
				case 'CustomerCustomerSignupVerification':
					return saveSMS('CustomerCustomerSignupVerification', jQuery(document).find('textarea[name="CustomerCustomerSignupVerification"]').val());
					break;
				
				case 'CustomerOrderPlaced':
					return saveSMS('CustomerOrderPlaced', jQuery(document).find('textarea[name="CustomerOrderPlaced"]').val());
					break;
				
				case 'CustomerOrderStatusChanged':
					return saveSMS('CustomerOrderStatusChanged', jQuery(document).find('textarea[name="CustomerOrderStatusChanged"]').val());
					break;
				
				case 'AdminCustomerSignup':
					return saveSMS('AdminCustomerSignup', jQuery(document).find('textarea[name="AdminCustomerSignup"]').val());
					break;
				
				case 'AdminCustomerSignupScheduled':
					return saveSMS('AdminCustomerSignupScheduled', jQuery(document).find('textarea[name="AdminCustomerSignupScheduled"]').val());
					break;
				
				case 'AdminOrderPlaced':
					return saveSMS('AdminOrderPlaced', jQuery(document).find('textarea[name="AdminOrderPlaced"]').val());
					break;
				
				case 'AdminOrderReturnRequest':
					return saveSMS('AdminOrderReturnRequest', jQuery(document).find('textarea[name="AdminOrderReturnRequest"]').val());
					break;
				
				case 'AdminContactInquiry':
					return saveSMS('AdminContactInquiry', jQuery(document).find('textarea[name="AdminContactInquiry"]').val());
					break;
				
				default:
					break;
			}
		}
		
		function saveAll(){
			
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
					console.log(response);
					jQuery('#'+_key+'Loader').fadeOut();
				},
				error: function(response){
					console.log('\nERR: ');
					console.log(response);
					jQuery('#'+_key+'Loader').fadeIn();
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
			.col-xs-4 {
				margin-left: 80px;
				width: 33.3333%;
			}
		</style>
	</head>
	<body>
		<div class="col-xs-12 padding-top sms-config">
			<h3 class="alert alert-info">Configuration</h3>
			<form>
				<div class="box container well col-xs-12" style="padding: 0">
					<ul class="tabs nav">
						<li class="col-xs-6 active"><a href="#customer-sms-alerts">Customer SMS Alerts</a></li>
						<li class="col-xs-6"><a href="#admin-sms-alerts">Admin SMS Alerts</a></li>
					</ul>
					<div id="customer-sms-alerts" class="nav-content" style="display: block;">
						<div class="customer-signup customer-alerts">
							<div id="CustomerCustomerSignupLoader" style="display: none;" class="single-loader">
								<div style="margin: 80px 0px 0px 40%;">
									<div style="margin-left: 80px;">Saving</div>
									<img src="https://cdn.shopify.com/s/files/1/1141/9304/files/loader.gif?12050156025147783748" alt="Saving" title="Saving">
								</div>
							</div>
							<div class="col-xs-4">
								<h4>New Customer Signup:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
										<p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea" name="CustomerCustomerSignup"></textarea>
								</div>
							</div>
							<div class="col-xs-11 text-right">
								<p></p>
								<a href="javascript: void(0);" class="btn btn-info" onclick="return sendTestSMS('CustomerCustomerSignup');">Send Test SMS</a>
								<a class="btn btn-success" href="javascript: void(0);" onclick="return save('CustomerCustomerSignup');">Save</a>
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
								<h4>New Customer Signup verification:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [verification_code].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<p style="margin-top: 30px;"></p>
									<textarea class="sms-textarea" name="CustomerCustomerSignupVerification"></textarea>
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
								<h4>New Order Placed:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname],[customer_address],[customer_postcode],[customer_city],[customer_country],[order_id],[order_total],[order_products_count],[order_status].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea" name="CustomerOrderPlaced"></textarea>
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
								<h4>Order status changed:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname], [customer_address], [customer_postcode],[customer_city],[customer_country],[order_id],[order_total],[order_products_count],[order_old_status],[order_new_status],[order_date].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									 <p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea" name="CustomerOrderStatusChanged"></textarea>
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
										<textarea class="sms-textarea" name="AdminCustomerSignup"></textarea>
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
										 <textarea class="sms-textarea" name="AdminCustomerSignupScheduled"></textarea>
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
									 <textarea class="sms-textarea" name="AdminOrderPlaced"></textarea>
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
									 <textarea class="sms-textarea" name="AdminOrderReturnRequest"></textarea>
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
									 <textarea class="sms-textarea" name="AdminContactInquiry"></textarea>
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
					<div class="col-xs-12 text-right" style="padding: 20px;">
						<a href="javascript: void(0);" class="btn btn-success" onClick="return saveAll();">Save all configuration</a>
					</div>					
				</div>
			</form>
		</div>
	</body>
</html>
