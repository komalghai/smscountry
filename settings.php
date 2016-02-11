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
						<div class="new-customer-signup customer-alerts">
							<div class="col-xs-4">
								<h4>New Customer Signup:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname].
								</code>
							</div>
							<div class="col-xs-6 right">
								<div class="form-group">
									<p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea"></textarea>
								</div> 
								<button class="btn btn-info send-test-sms">Send Test SMS</button> 
							</div>
							
						</div>
						<div class="new-customer-signup-verification customer-alerts">
							<div class="col-xs-4">
								<h4>New Customer Signup verification:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [verification_code].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<p style="margin-top: 30px;"></p>
									<textarea class="sms-textarea"></textarea>
								</div> 
								<button class="btn btn-info send-test-sms">Send Test SMS</button> 
							</div>
							
						</div>
						<div class="new-order-placed-by-the-customer customer-alerts">
							<div class="col-xs-4">
								<h4>New Order Placed:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname],[customer_address],[customer_postcode],[customer_city],[customer_country],[order_id],[order_total],[order_products_count],[order_status].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea"></textarea>
								</div> 
								<button class="btn btn-info send-test-sms">Send Test SMS</button> 
							</div>
							
						</div>
						<div class="Admin-Has-Changed-The-Status-Of-The-Customer-Order customer-alerts">
							<div class="col-xs-4">
								<h4>Order status changed:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname], [customer_address], [customer_postcode],[customer_city],[customer_country],[order_id],[order_total],[order_products_count],[order_old_status],[order_new_status],[order_date].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									 <p style="margin-top: 30px;"></p>
									 <textarea class="sms-textarea"></textarea>
								</div> 
								<button class="btn btn-info send-test-sms">Send Test SMS</button> 
							</div>
							
						</div>
					</div>
					<div id="admin-sms-alerts" class="nav-content">
						<div class="new-customer-signup customer-alerts">
							<div class="col-xs-4">
								<h4>New Customer Signup:</h4>
								<code>
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group"> 
									<p style="margin-top: 30px;"></p>
									<textarea class="sms-textarea"></textarea>
								</div>
							</div>
							<div class="col-xs-2">
								<button class="btn btn-info">Send Test SMS</button>
							</div>
							<div class="col-xs-4">
									<h4>Possible Custom variables-(If it's for Single Customer):</h4>
									<code>
										You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname].
									</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group"> 
									<p style="margin-top: 30px;"></p>
									<textarea class="sms-textarea"></textarea>
								</div>
							</div>
							<div class="col-xs-2">
								<button class="btn btn-info">Send Test SMS</button>
							</div>
							<div class="col-xs-4">
								<h4>Possible Custom variables-(If it's based on daily,weekly or monthly):</h4>
								<code>
									You can use following variables: <br>[shop_name], [shop_domain], [customer_count].  
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group"> 
									<p style="margin-top: 30px;"></p>
									<textarea class="sms-textarea"></textarea>
								</div>
							</div>
							<div class="col-xs-2">
								<button class="btn btn-info">Send Test SMS</button>
							</div>
						</div>	
					</div>					
				</div>
			</form>
		</div>
	</body>
</html>
