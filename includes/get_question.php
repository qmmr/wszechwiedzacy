<?php

    require_once("initialize.php");
    
    $ja = array(
				    "id" => "nothing",
				    "runda" => "nothing",
				    "kategoria" => "nothing",
				    "tresc" => "nothing",
				    "odp_a" => "nothing",
				    "odp_b" => "nothing",
				    "odp_c" => "nothing",
				    "odp_d" => "nothing",
				    "poprawna" => "nothing",
				    "link" => "nothing"
				    );
    
    if(isset($_POST['id'])) {
	
	$question = Question::find_by_id($_POST['id']);
	$arr = array();
	
	foreach ($question as $key => $value) {
	    
	    $arr[] = $value;
	    
	}
	
	$ja["id"] 			= $arr[0];
	$ja["runda"] 		= $arr[4];
	$ja["kategoria"] 	= $arr[5];
	$ja["tresc"] 		= $arr[6];
	$ja["odp_a"] 		= $arr[7];
	$ja["odp_b"] 		= $arr[8];
	$ja["odp_c"] 		= $arr[9];
	$ja["odp_d"] 		= $arr[10];
	$ja["poprawna"] 	= $arr[11];
	$ja["link"] 			= $arr[12];
	
    }
    
    // return the value to jQuery via ajax to confirm if the login was succesful
    echo json_encode($ja);
    
?>