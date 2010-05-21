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
    'temat' => 'Wiesz, czy nie wiesz? Podsumowanie miesiąca.',
    'username' => 'agn0stic',
    'email' => 'bez.niczego@gmail.com',
    'key' => '23'
);

$q = "SELECT id FROM users WHERE active = 0 LIMIT 10";
$r = $db->query($q);
while($row = mysql_fetch_assoc($r)) {
    $results[] = $row['id'];
}
$str = implode(",",$results);

if($db->affected_rows() != 0) {
    
    $q = "DELETE FROM users WHERE id IN (" . $str .")";
    $r = $db->query($q);
    if($r) {
        echo "ids deleted " . $str . " czyli ilosc " . $db->affected_rows() . " graczy zostało usuniętych";
    } else {
        echo "problem " .mysql_error();
    }
	
} else {
    
    echo "no inactive accounts found.";
    
}
//send the email
//$s = send_email($info, 'newsletter');
//($s) ? true : false;