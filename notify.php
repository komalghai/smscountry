<?php 
$to = "prashant.3ginfo@gmail.com";
$subject = "HTML email";
$msg = "An order has been placed!";
/* $msg .= "<pre>";
$msg .= var_dump($_REQUEST);
$msg .= "</pre>"; */
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: <webmaster@example.com>' . "\r\n";

if(mail($to,$subject,$msg,$headers)){
    die("Mail sent to: {$to}");
} else {
	echo "<pre>";
	print_r(error_get_last());
    die("Mail not sent!");
}
?> 
