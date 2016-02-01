
<script>alert(1);</script>;
<?php echo "https://smsappstore.myshopify.com/admin/webhooks.json??access_token=".$_SESSION['oauth_token'];?>
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script>
  
$.ajax({
	type: 'POST',
	url: "https://smsappstore.myshopify.com/admin/webhooks.json??access_token=" + <?php echo $_SESSION['oauth_token']; ?>,  
	dataType:'json',
     data: {
  "webhook": {
    "topic": "orders\/create",
    "address": "https:\/\/smscountry.herokuapp.com\/",
    "format": "json"
  }
},
success: function(response){
  alert('yes');
}

});
  
</script>
