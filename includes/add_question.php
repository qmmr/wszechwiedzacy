<?php
    require_once("initialize.php");
    $json_arr = array("id" => "nothing", "runda" => "nothing", "kategoria" => "nothing", "tresc" => "nothing", "odp_a" => "nothing", "odp_b" => "nothing", "odp_c" => "nothing", "odp_d" => "nothing", "poprawna" => "nothing", "link" => "nothing", "success" => "false");
    if(isset($_POST["runda"])) {
	$time = date("Y-m-j H:i:s", time());
	$runda = trim(mysql_prep($_POST['runda']));
	$autor = trim(mysql_prep($_POST['autor']));
	$kategoria = trim(mysql_prep($_POST['kategoria']));
	$tresc = trim(mysql_prep($_POST['tresc']));
	$odp_a = trim(mysql_prep($_POST['odp_a']));
	$odp_b = trim(mysql_prep($_POST['odp_b']));
	$odp_c = trim(mysql_prep($_POST['odp_c']));
	$odp_d = trim(mysql_prep($_POST['odp_d']));
	$poprawna = trim(mysql_prep($_POST['poprawna']));
	$link = trim(mysql_prep($_POST['link']));
	$json_arr["runda"] = $runda;
	$json_arr["kategoria"] = $kategoria;
	$json_arr["tresc"] = $tresc;
	$json_arr["odp_a"] = $odp_a;
	$json_arr["odp_b"] = $odp_b;
	$json_arr["odp_c"] = $odp_c;
	$json_arr["odp_d"] = $odp_d;
	$json_arr["poprawna"] = $poprawna;
	$json_arr["link"] = $link;
	$query = "INSERT INTO pytania (";
	$query .= "autor, ";
	$query .= "data_dodania, ";
	$query .= "ostatnia_aktualizacja, ";
	$query .= "runda, ";
	$query .= "kategoria, ";
	$query .= "tresc, ";
	$query .= "odpowiedz_a, ";
	$query .= "odpowiedz_b, ";
	$query .= "odpowiedz_c, ";
	$query .= "odpowiedz_d, ";
	$query .= "poprawna, ";
	$query .= "link) ";
	$query .= "VALUES (";
	$query .= " '{$autor}', ";
	$query .= "'{$time}', ";
	$query .= "'{$time}', ";
	$query .= " '{$runda}', ";
	$query .= " '{$kategoria}', ";
	$query .= " '{$tresc}', ";
	$query .= " '{$odp_a}', ";
	$query .= " '{$odp_b}', ";
	$query .= " '{$odp_c}', ";
	$query .= " '{$odp_d}', ";
	$query .= " '{$poprawna}', ";
	$query .= " '{$link}')";
	$result = mysql_query($query);
	if ($result) {
	    // pytanie zostało dodane
	    $json_arr['id'] = mysql_insert_id();
	    $json_arr['success'] = "true";
	} else {	    
	    // błąd podczas dodawania pytania
	    $json_arr['mysql'] = "error";
	}
    } else {
	$json_arr['success'] = "false";
    }
    // return the value to jQuery via ajax to confirm if the login was succesful
    echo json_encode($json_arr);
?>