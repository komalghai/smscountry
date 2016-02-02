<?php

define('SHOPIFY_APP_SECRET', 'a8f2eb7507462a123baf628023856291-1454413069');

function verify_webhook($data, $hmac_header)
{
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
  return ($hmac_header == $calculated_hmac);
}


$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$data = file_get_contents('php://input');
$verified = verify_webhook($data, $hmac_header);
echo "<pre>";
print_r(var_export($verified, true));
exit();
?>
