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

// sets timezone
$db->query("SET time_zone = '+02:00'");
$q = "DELETE FROM users WHERE active = 0 AND register_date <= DATE_SUB(NOW(), INTERVAL 96 HOUR) LIMIT 100";
$r = $db->query($q);

if($db->affected_rows() != 0) {
    
    $num = $db->affected_rows();
    $db->query("DELETE FROM confirm WHERE reminded = 1 AND reg_date <= DATE_SUB(NOW(), INTERVAL 96 HOUR) LIMIT 100");
    
    ($num == 1) ? $a = "was" : $a = "were";
    echo $num . " inactive accounts " . $a . " deleted from users and ". $db->affected_rows() . " from confirm.";
    
} else {
    
    echo "no inactive accounts found!";
        
}

?>