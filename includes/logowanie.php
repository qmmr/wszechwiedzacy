<?php
require_once("initialize.php");
$ja = array("email" => "valid","password" => "valid","logged" => "false","game" => "false");

if(isset($_POST['email']) && isset($_POST['password'])) {
	
	$email = trim(mysql_prep($_POST['email']));
	$password = trim(mysql_prep($_POST['password']));
	$ja['game'] = $_POST['game'];
	$found_user = User::authenticate($email, $password);
	if($found_user) {
		
		if($found_user->active == 1) {
			
			// login user if he was found in database and password is correct and active is set to 1
			$session->login($found_user);
			$ja['logged'] = "true";
			
		} else {
			
			$ja["email"] = "inactive";
			
		}
		
	} else {
		
		// if the $found_user is false (not found) we check if the user exists in database
		$email_exists = User::email_exists($email);
		if($email_exists) {
			
			// if user exists it means the password was wrong and we can display msg in jQuery
			if($email_exists->active == 1) {
				
				$ja["password"] = "invalid";
				
			} else {
				
				$ja["email"] = "inactive";
				
			}
			
		} else {
			
			// if the user does not exist we set the user to invalid so we can use it in jQuery
			$ja["email"] = "invalid";
			
		}
		
	}
	
} else {
	
	// if there was no $_POST (ie. entering logowanie.php in browser)
	($session->is_logged_in()) ? $session->logout() : false;
	
}
// return the value to jQuery via ajax to confirm if the login was succesful
echo json_encode($ja);
?>