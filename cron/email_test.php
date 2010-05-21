<?php

define("TESTING_GROUND", false);
defined("DS")           ? null : define("DS", "/");
define("SITE_ROOT", "/home/wszechwi/public_html/");
defined("SITE_URL")     ? null : define("SITE_URL", "http://" . $_SERVER['HTTP_HOST'] . "/");
defined("LIB_PATH")     ? null : define("LIB_PATH", SITE_ROOT."includes");

require_once(LIB_PATH.DS."config.php");
require_once(LIB_PATH.DS."functions.php");
require_once(LIB_PATH.DS."database.php");
require_once(LIB_PATH.DS."question.php");

require_once '../lib/swift_required.php';

//$q = Question::how_many_questions();
//$rn = mt_rand(0, $q); 
$msg = $_SERVER['DOCUMENT_ROOT']; // . " Liczba pytań w bazie danych = " . $q . " a wylosowane pytanie to -> " . $rn;

//Create the Transport the call setUsername() and setPassword()
//$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
//    ->setUsername('bez.niczego@gmail.com')
//    ->setPassword('+#3r3!zIVo*G0D')
//    ;

//Create the Mailer using your created Transport
//$mailer = Swift_Mailer::newInstance($transport);

//Create a message
//$message = Swift_Message::newInstance('Testowo')
//  ->setFrom(array('bez.niczego@gmail.com' => 'i dont care'))
//  ->setTo(array('kumeek@gmail.com' => 'qmmr'))
//  ->setBody($msg)
//  ;

$info = array(
            'temat' => 'Co nowego u wszechwiedzącego?',
			'username' => 'lojalny użytkownik',
			'email' => 'bez.niczego@gmail.com',
			'key' => '23'
);
 
//send the email
if(send_email($info, 'newsletter')) {

	//email sent
	//$ja['email'] = "sent";

} else {

	// email was not sent
	//$ja['email'] = "error";

}

//$root = $_SERVER['DOCUMENT_ROOT'].'wszechwiedzacy/rejestracja';
//$template = file_get_contents($root . '/template.html');
//$cid = $message->embed(Swift_Image::fromPath('http://net.tutsplus.cdn.plus.org/wp-content/themes/tuts_theme/images/logo.gif'));
//$cid = $message->embed(Swift_Image::fromPath('images/soon.png'));
//$message->setBody($template, 'text/html');
//$message->attach(Swift_Attachment::fromPath('http://wszechwiedzacy.com/rejestracja/header.gif'));

//Send the message
//$result = $mailer->send($message);
//$result = $mailer->batchSend($message);
//if($result) {
//    echo "email was sent!";
//}