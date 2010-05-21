<?php
require_once("initialize.php");

$json_arr = array("username" => "none", "msg" => "none");

if(isset($_POST)) {

	global $database;

	$username = trim($db->escape_value($_POST['username']));
	// finds user and stores values as object
	$cu = User::find_user($username);

	(isset($_POST['wiek']) && $_POST['wiek'] != "undefined") ? $wiek = 1 : $wiek = 0;
	(isset($_POST['sex']) && $_POST['sex'] != "undefined") ? $sex = trim($db->escape_value($_POST['sex'])) : $sex = 0;
	(isset($_POST['city']) && $_POST['city'] != "") ? $city = trim($db->escape_value($_POST['city'])) : $city = "";
	(isset($_POST['degree']) && $_POST['degree'] != "wybierz") ? $degree = trim($db->escape_value($_POST['degree'])) : $degree = "";
	(isset($_POST['newsletter']) && $_POST['newsletter'] != "undefined") ? $newsletter = 1 : $newsletter = 0;
	if ($_POST['old_pass'] != "") {
	  
		$old_pass = trim($db->escape_value($_POST['old_pass']));
		$oph = sha1($old_pass);
	  
	}
	($_POST['new_pass'] != "") ? $new_pass = trim($db->escape_value($_POST['new_pass'])) : false;
	($_POST['new_pass2'] != "") ? $new_pass2 = trim($db->escape_value($_POST['new_pass2'])) : false;

	/**
	 *	check if old pass is not the same as new, new pass and new pass2 match and if old pass is same as in db
	 */
	if($old_pass == $new_pass && $old_pass != "") {
	  
		$json_arr['msg'] = "old equals new";
	  
	} elseif ($new_pass != $new_pass2) {
	  
		$json_arr['msg'] = "sprawdź czy dobrze przepisałeś nowe hasło";
	  
	} elseif ($oph != $cu->hashed_password) {
		 
		$json_arr['msg'] = "wrong old password";
	  
	} else {
	  
		$json_arr['msg'] = "correct";
	  
	}

	// if no problems with passwords we can update db
	if($json_arr['msg'] == "none" || $json_arr['msg'] == "correct") {
	  
		$hp = sha1($new_pass);
	  
		$sql = "UPDATE users SET ";
		if ($json_arr['msg'] == "correct") { $sql .= "hashed_password = '$hp', "; }
		$sql .= "show_wiek = '{$wiek}', ";
		$sql .= "sex = '{$sex}', ";
		$sql .= "city = '{$city}', ";
		$sql .= "degree = '{$degree}', ";
		$sql .= "subscribed = '{$newsletter}' ";
		$sql .= "WHERE user_name = '{$username}' LIMIT 1";
	  
		$result = $database->query($sql);
		if($result) {

			// updated
			$json_arr['msg'] = "ok";

		} else {

			// update failed
			$json_arr['msg'] = "mysql query failed";

		}
	  
	} // end of password check conditional

} else {

	// if there was no $_POST (ie. entering logowanie.php in browser)
	if($session->is_logged_in()) {
	  
		$session->logout();
	  
	}

}

// return the value to jQuery via ajax to confirm if the login was succesful
echo json_encode($json_arr);

?>