<?php

require_once("initialize.php");
include_once '../lib/swift_required.php';

$ja = array('msg' => 'invalid');

if(isset($_POST['email'])) {
	
	//format each email
	//$body = format_email($info, 'html');
	$body_plain_txt = $_POST['msg'];
    
    $ef = 'bez.niczego@gmail.com';
    $p = '+#3r3!zIVo*G0D';
    
	//setup the mailer
    if(TESTING_GROUND){
        
        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername($ef)
            ->setPassword($p)
            ;
            
    } else {
        
        $transport = Swift_MailTransport::newInstance();
        
    }
	
	$mailer = Swift_Mailer::newInstance($transport);
    
	$message = Swift_Message::newInstance();
	$message ->setSubject($_POST['temat']);
	$message ->setFrom(array($ef => 'formularz kontaktowy'));
	$message ->setTo(array('kontakt@wszechwiedzacy.pl' => 'kontakt'));
	$message ->setBody($body_plain_txt);
	//$message ->addPart($body, 'text/html');
    
	$mailer->send($message);
	
    // confirm sending mail to jQuery
	($mailer) ? $ja['msg'] = "valid" : false;
	
}

echo json_encode($ja);

?>