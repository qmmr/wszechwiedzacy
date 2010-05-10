<?php
    require_once("initialize.php");
    $json_arr = array("id" => "nothing", "runda" => "nothing", "kategoria" => "nothing", "tresc" => "nothing", "odp_a" => "nothing", "odp_b" => "nothing", "odp_c" => "nothing", "odp_d" => "nothing", "poprawna" => "nothing", "komentarz" => "nothing");
    if(isset($_POST['id'])) {
	$id = $_POST['id'];
	$query = "DELETE FROM pytania WHERE id = '{$id}' LIMIT 1";
	$result = mysql_query($query);
	if($result) {
	    $json_arr['id'] = $id;
	}
    } 
    // return the value to jQuery via ajax to confirm if the login was succesful
    echo json_encode($json_arr);
?>