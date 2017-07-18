<?php
$mail = new PHPMailer(true); 
if($phpmailer_config=json_decode($db->get_var("select TokenText from tokens where GUID ='2090E253-CA1F-0B80-6A81-F4862600AEEB'"))) {		
						
	// the true param means it will throw exceptions on errors, which we need to catch
	if($phpmailer_config->IsSMTP) {
		$mail->IsSMTP();  // Set mailer to use SMTP
	}                   
	$mail->Host = $phpmailer_config->Host;  // Specify main and backup server
	$mail->SMTPAuth = $phpmailer_config->SMTPAuth;             // Enable SMTP authentication
	$mail->Username = $phpmailer_config->Username;    // SMTP username
	$mail->Password = $phpmailer_config->Password;     // SMTP password
	$mail->SMTPSecure = $phpmailer_config->SMTPSecure;
}
else {
	$mail->IsSMTP();                    // Set mailer to use SMTP
	$mail->Host = MAIL_HOST;  // Specify main and backup server
	$mail->SMTPAuth = true;             // Enable SMTP authentication
	$mail->Username = MAIL_USERNAME;    // SMTP username
	$mail->Password = MAIL_PASSWORD;     // SMTP password
	if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='cdn.jobshout.com') {

	}else {	
		$mail->SMTPSecure = 'tls';
	}
}

?>
