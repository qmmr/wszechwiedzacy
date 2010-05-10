<?php

include_once '../lib/swift_required.php';

$ja = array('msg' => 'invalid', 'recaptcha' => 'none');

if(isset($_POST['email'])) {
	
	//format each email
	//$body = format_email($info, 'html');
	$body_plain_txt = $_POST['msg'];
	//setup the mailer
	$transport = Swift_MailTransport::newInstance();
	$mailer = Swift_Mailer::newInstance($transport);
	$message = Swift_Message::newInstance();
	$message ->setSubject($_POST['temat']);
	$message ->setFrom(array($_POST['email'] => $_POST['name']));
	$message ->setTo(array('kontakt@wszechwiedzacy.pl' => 'kontakt'));
	$message ->setBody($body_plain_txt);
	//$message ->addPart($body, 'text/html');
	$mailer->send($message);
	
	if($mailer) {
		
		$ja['msg'] = "valid";
		
	}
	
}

echo json_encode($ja);

?>