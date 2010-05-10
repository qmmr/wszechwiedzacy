<?php

require_once("initialize.php");

$session->clear_stats();
$_SESSION['current_round'] = 1;

$ja['round'] = $_SESSION['current_round'];

echo json_encode($ja);

?>