<?php
require_once '../lib/swift_required.php';
  
//Create the Transport the call setUsername() and setPassword()
$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
    ->setUsername('bez.niczego@gmail.com')
    ->setPassword('+#3r3!zIVo*G0D')
    ;

//Create the Mailer using your created Transport
$mailer = Swift_Mailer::newInstance($transport);

//Create a message
$message = Swift_Message::newInstance('Testowo')
  ->setFrom(array('bez.niczego@gmail.com' => 'i dont care'))
  ->setTo(array('kumeek@gmail.com' => 'qmmr'))
  ->setBody('Testowa wiadomość jest trochę długa, ale muszę zobaczyć jak to wygląda. Wstawię jeszcze jakiś html <b>To jest bold</b>')
//  ->setTo(array('kumeek@gmail.com' => 'qmmr', 'chory.katol@gmail.com' => 's!ckatol'))
  ;

//$root = $_SERVER['DOCUMENT_ROOT'].'wszechwiedzacy/rejestracja';
//$template = file_get_contents($root . '/template.html');
//$cid = $message->embed(Swift_Image::fromPath('http://net.tutsplus.cdn.plus.org/wp-content/themes/tuts_theme/images/logo.gif'));
//$cid = $message->embed(Swift_Image::fromPath('images/soon.png'));
//$message->setBody($template, 'text/html');
//$message->attach(Swift_Attachment::fromPath('http://wszechwiedzacy.com/rejestracja/header.gif'));

//Send the message
$result = $mailer->send($message);
//$result = $mailer->batchSend($message);
if($result) {
    //echo "email was sent!";
}