<?php

require_once ("../includes/initialize.php");
$ja = array();
if(isset($_POST['email'])) {

    $email = $db->escape_value($_POST['email']);
    if(User::email_exists($email)) {
        $r = $db->query("UPDATE users SET subscribed = 0 WHERE email = '{$email}' LIMIT 1");
        if($db->affected_rows() == 1) {
            $ja['outcome'] = "ok";
            $ja['msg'] = $email;
            echo json_encode($ja);
        } else {
            $ja['outcome'] = "bad";
            $ja['msg'] = $email . " nie byl zapisany do listy mailingowej";
            echo json_encode($ja);
        } // end affected rows
    } else {
        $ja['outcome'] = "bad";
        $ja['msg'] = $email . " nie ma takiego adresu w naszej bazie!";
        echo json_encode($ja);
    } // end email exists
} else {
    $ja['outcome'] = "bad";
    $ja['msg'] = "Nie otrzymalem adresu email!";
    echo json_encode($ja);
} // end isset email

?>