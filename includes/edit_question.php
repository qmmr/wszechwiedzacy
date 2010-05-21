<?php
    require_once("initialize.php");
    $json_arr = array("id" => "nothing", "runda" => "nothing", "kategoria" => "nothing", "tresc" => "nothing", "odp_a" => "nothing", "odp_b" => "nothing", "odp_c" => "nothing", "odp_d" => "nothing", "poprawna" => "nothing", "link" => "nothing");
    if(isset($_POST['id'])) {
	$time = date("Y-m-j H:i:s", time());
	$id = $_POST['id'];
	$runda = trim($db->escape_value($_POST['runda']));
	$autor = trim($db->escape_value($_POST['autor']));
	$kategoria = trim($db->escape_value($_POST['kategoria']));
	$tresc = trim($db->escape_value($_POST['tresc']));
	$odp_a = trim($db->escape_value($_POST['odp_a']));
	$odp_b = trim($db->escape_value($_POST['odp_b']));
	$odp_c = trim($db->escape_value($_POST['odp_c']));
	$odp_d = trim($db->escape_value($_POST['odp_d']));
	$poprawna = trim($db->escape_value($_POST['poprawna']));
	$link = trim($db->escape_value($_POST['link']));
	$json_arr["id"] = $id;
	$json_arr["runda"] = $runda;
	$json_arr["kategoria"] = $kategoria;
	$json_arr["tresc"] = $tresc;
	//$json_arr["odp_a"] = $odp_a;
	//$json_arr["odp_b"] = $odp_b;
	//$json_arr["odp_c"] = $odp_c;
	//$json_arr["odp_d"] = $odp_d;
	//$json_arr["poprawna"] = $poprawna;
	//$json_arr["komentarz"] = $komentarz;
	$query = "UPDATE pytania SET ";
	$query .= "ostatnia_aktualizacja = '{$time}', ";
	$query .= "runda = '{$runda}', ";
	$query .= "kategoria = '{$kategoria}', ";
	$query .= "tresc = '{$tresc}', ";
	$query .= "odpowiedz_a = '{$odp_a}', ";
	$query .= "odpowiedz_b = '{$odp_b}', ";
	$query .= "odpowiedz_c = '{$odp_c}', ";
	$query .= "odpowiedz_d = '{$odp_d}', ";
	$query .= "poprawna = '{$poprawna}', ";
	$query .= "link = '{$link}' ";
	$query .= "WHERE id = '{$id}' LIMIT 1";
	$result = mysql_query($query);
	if ($result) {
	    // pytanie zostało dodane
	    //$json_arr['pytanie'] = "success";
	} else {
	    // błąd podczas dodawania pytania
	    //$json_arr['mysql'] = "error";
	}
	//$json_arr['runda'] = "przedostatnia";
    }
    // return the value to jQuery via ajax to confirm if the login was succesful
    echo json_encode($json_arr);
?>