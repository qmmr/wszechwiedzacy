<?php
	/*
	// recaptcha
	require_once('recaptchalib.php');
	$privatekey = "6LcCxgoAAAAAAPnTryh6FnuDdzhXqW8b-Jpm0iQD";
	// the response from reCAPTCHA
	$resp = null;
	// was there a reCAPTCHA response?
	if ($_POST["recaptcha_response_field"]) {
		
		$resp = recaptcha_check_answer (
		$privatekey,
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]
		);
		
		if ($resp->is_valid) {
			
			$ja['recaptcha'] = 'valid';
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
			
		} else {
			
			$ja['recaptcha'] = $resp->error;
			
		}
		
	}
	*/