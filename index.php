<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script>
  
$.ajax({
	type: 'POST',
	url: '/admin/webhooks.json',  
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
