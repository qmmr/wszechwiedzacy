<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<title>Test</title>
</head>
<body>
<h1>Testujemy wysyłanie emaili</h1>
<?php

define("TESTING_GROUND", false);
define("DS", "/");
define("SITE_ROOT", "/home/wszechwi/public_html/");
define("SITE_URL", "http://wszechwiedzacy.pl/");
define("LIB_PATH", SITE_ROOT."includes");

require_once(LIB_PATH.DS."config.php");
require_once(LIB_PATH.DS."functions.php");
require_once(LIB_PATH.DS."database.php");
require_once(LIB_PATH.DS."question.php");

require_once(SITE_ROOT.DS."lib/swift_required.php");

$info = array(
    'temat' => 'Aktualizacja do wersji beta 0.8',
    'key' => '23'
);

$q = "SELECT user_name, email FROM users WHERE subscribed = 1 LIMIT 300";
$r = $db->query($q);

while($row = mysql_fetch_assoc($r)) {
    
    $info['username'] = $row['user_name'];
    $info['email'] = $row['email'];
    
   	//format each email
	$body = format_email($info, 'html', 'test');
	$body_plain_txt = format_email($info, 'txt', 'test');
    
	//setup the mailer
	$transport = Swift_MailTransport::newInstance();
	$mailer = Swift_Mailer::newInstance($transport);
    
	$message = Swift_Message::newInstance();
	$message ->setSubject($info['temat']);
	$message ->setFrom(array('kontakt@wszechwiedzacy.pl' => 'wszechwiedzacy.pl'));
	$message ->setTo(array($info['email'] => $info['username']));
	$message ->setBody($body_plain_txt);
	$message ->addPart($body, 'text/html');
	$result = $mailer->send($message);
    if($result) {
        echo "<h2>Mail to " . $info['username'] . " was successfully sent!</h2>";
    } else {
        echo "Mail to " . $info['username'] . " was NOT sent!";
    }
}
?>
</body>
</html>