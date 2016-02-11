<!DOCTYPE html>
<html>
	<head>
		<title>Configuration - SMS Country</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<style type="text/css">
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
						<div class="new-customer-signup">
							<div class="col-xs-4">
								<h4>New Customer Signup:</h4>
								<code class="code">
									You can use following variables: <br>[shop_name], [shop_domain], [customer_firstname], [customer_lastname].
								</code>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<p></p>
									<input type="email" class="form-control" id="email">
								</div>
								<div class="col-xs-2">
								<button class="btn btn-info">Send Test SMS</button>
							</div>
							</div>
							
						</div>
					</div>
					<div id="admin-sms-alerts" class="nav-content">
						<div class="col-xs-4">
							<h4>New Customer Signup:</h4>
							<code>
								You can use following variables: [shop_name], [shop_domain], [customer_firstname], [customer_lastname].
							</code>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label for="email"></label>
								<input type="email" class="form-control" id="email">
							</div>
						</div>
						<div class="col-xs-2">
							<button class="btn btn-info">Send Test SMS</button>
						</div>
					</div>   
					<button type="submit" class="btn btn-success">Submit</button>				
				</div>
			</form>
		</div>
	</body>
</html>
