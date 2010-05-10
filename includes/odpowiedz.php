<?php

require_once("initialize.php");

if(isset($_POST['group'])) {

	$answer = $_POST['group'];
	$encrypted = md5($answer);
	$poprawna = $_POST['poprawna'];
	$punkty = $_POST['punkty'];

	// this checks if the correct radio button was selected
	if($encrypted == $poprawna) {
	  
		// if yes, we add point and count number of correct answers
		$session->odpowiedz(true, $punkty);
	  
	} else {
		
		// stores your answer (player's)
		$_SESSION['current_question']['ya'] = $answer;
	  
		$session->odpowiedz(false);
		if(!isset($_SESSION['first_wrong'])) {

			$_SESSION['first_wrong'] = $_SESSION['current_question'];

		} else if (isset($_SESSION['first_wrong']) && (!isset($_SESSION['second_wrong']))) {

			$_SESSION['second_wrong'] = $_SESSION['current_question'];

		} else {

			$_SESSION['third_wrong'] = $_SESSION['current_question'];

		}
	  
	}

}

$ja = array(
				"game_state" => "false",
				"current_round" => 1
				);
				
switch($_SESSION['question_number']) {

	case 5:
		$_SESSION['current_round'] ++;
		$ja["game_state"] = "advance";
		break;

	case 15:
		$_SESSION['current_round'] ++;
		$ja["game_state"] = "advance";
		break;

//	case 12:
//		$_SESSION['current_round'] ++;
//		$ja["game_state"] = "advance";
//		break;

//	case 20:
//		$_SESSION['current_round'] ++;
//		$ja["game_state"] = "advance";
//		break;

	default:
		break;
}

// if we lost all chances we set game_end to true so it can be read by jQuery ajax json
if($_SESSION['chances'] == 0) {

	$ja["game_state"] = "over";

} else {

	$ja["current_round"] = $_SESSION['current_round'];

}

// we pass params as json array (not needed for one param but good to know how to pass several params)
// if we wanted simple one we just echo $game = "false"
echo json_encode($ja);

?>