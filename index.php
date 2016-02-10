<?php 
require __DIR__.'/conf.php'; 
global $db;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Settings - SMS Country</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="col-xs-12">
			<div class="container well">
				<?php if(isset($_GET['conf']) && ($_GET['conf'] == 200)){ ?>
					<div class="alert alert-fade alert-success">App installed successfully!</div>
				<?php } ?>
				<ul class="tabs">
					<li><a href="#tab-1">Customer SMS Alerts</a></li>
					<li><a href="#tab-2">Admin SMS Alerts</a></li>
				</ul>
				<div id="tab-1">
					content 1
				</div>
				<div id="tab-2">
					content 2
				</div>     
			</div>
		</div>
	</body>
</html>
