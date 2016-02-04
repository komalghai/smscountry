<?php 
require_once('php-mailer/class.phpmailer.php');
$address = "prashant.3ginfo@gmail.com";
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Host = "smtp.google.com";
$mail->Username = "prashant.3ginfo@gmail.com";
$mail->Password = "53*63@83%32#weed";
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
/* $body = file_get_contents('https://lh3.googleusercontent.com/-6TFgPgFH3lI/AAAAAAAAAAI/AAAAAAAAAAA/3KU8k8aPhKg/mo/photo.jpg?sz=46'); */
$body = 'testing shopify';
$mail->SetFrom('webmaster@webandweb.in', 'First Last');
$mail->AddAddress($address, "Developer");
$mail->Subject = "PHPMailer test on heroku server!!";
$mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
$mail->MsgHTML($body);
if(!$mail->Send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
} else {
	echo "Message sent!";
}
die;
?>
