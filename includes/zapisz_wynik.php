<?php

require_once("initialize.php");

$ja['id'] = "none";

if($_POST) {
	
	// zapisuje wynik
	$score = $_SESSION['zdobyte_punkty'];
	// if user is logged
	if(isset($_SESSION['username'])) {
		
		$name = $_SESSION['username'];
		$hashed_email = $_SESSION['hashed_email'];
		
	} else {
		
		$name =  trim( mysql_prep( $_POST['name'] ) );
		$hashed_email = md5($name);
		
	}
	
	$time = $_SESSION['question_start_time'] - $_SESSION['game_start_time'];
	// percentage of correct answers
	$perc = round(($_SESSION['odp_poprawne'] * 100) / $_SESSION['ilosc_odp']);	
	// returned value is id of inserted row
	$ja['id'] = User::insert_new_score($score, $name, $time, $perc, $_SESSION['odp_poprawne'], $hashed_email);
	// selects top 30 scores (default)
	$r = User::find_top_scores();
	// finds the position based on the points player scored (adding one cause of the zero based system)
	$ja['key'] = $key = (array_search($score, $r) + 1);
	
}

echo json_encode($ja);

?>