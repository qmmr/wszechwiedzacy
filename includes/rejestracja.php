<?php

require_once("initialize.php");

$ja = array("user" => "valid", "password" => "valid", "email" => "valid", "mysql" => "valid");

if(isset($_POST)) {

	$errors = array();
	$username = trim($db->escape_value($_POST['username']));
	$password = trim($db->escape_value($_POST['password']));
	$hashed_password = sha1($password);
	$email = trim($db->escape_value($_POST['email']));

	// check if user name hasn't been taken
	$user_exists = User::user_exists($username);
	if($user_exists) {

		array_unshift($errors, "username taken");
		$ja['user'] = "taken";

	}

	$email_check = (isValidEmail($email)) ? true : $ja['email'] = "error";

	// check if this email is in the database
	$email_exists = User::email_exists($email);
	if($email_exists) {

		array_unshift($errors, "email taken");
		$ja['email'] = "taken";

	}

	// no errors on first test, next we check if the username and password are of required length
	$fields_max_lengths = array("username" => 30, "password" => 30);
	$fields_min_lengths = array("username" => 3, "password" => 4);
	$errors = array_merge($errors, check_form_length($fields_max_lengths, true), check_form_length($fields_min_lengths, false));
	if(empty($errors)) {

		$time = date("Y-m-j H:i:s", time());
		$query = "INSERT INTO users ( ";
		$query .= "user_name, hashed_password, email, register_date, subscribed ) VALUES (";
		$query .= " '{$username}', '{$hashed_password}', '{$email}', '{$time}', 1 )";
		$result = mysql_query($query);
		if ($result) {
				
			/**
			 * we are going to send letter with confirmation
			 */
			$user_id = mysql_insert_id();
			$key = $username . $email . date("H:i:s");
			$skey = sha1($key);
			$query = "INSERT INTO confirm ( ";
			$query .= "user_id, confirm_key, email ) VALUES (";
			$query .= " '{$user_id}', '{$skey}', '{$email}' )";
				
			$result = mysql_query($query);
				
			if ($result) {
				 
				// confirm added successfully we send the email
				//include the swift class
				include_once '../lib/swift_required.php';
				//put info into an array to send to the function
				$info = array(
                            'temat' => 'Witamy w gronie użytkowników wszechwiedzacy.pl',
							'username' => $username,
							'email' => $email,
							'key' => $skey
				);
				 
				//send the email
//				if(send_email($info, 'register')) {
//
//					//email sent
//					$ja['email'] = "sent";
//
//				} else {
//
//					// email was not sent
//					$ja['email'] = "error";
//
//				}
                $ja['email'] = "sent";
				 
			} else {
				 
				$ja['mysql'] = "Oops! Nie mogłem dodać danych do tablicy confirm: " . mysql_error();
				 
			}
				
		} else {
				
			// error when creating user
			$ja['mysql'] = "Błąd podczas rejestracji użytkownika: " . mysql_error();
				
		} // end of $result if/else

	} // end of empty($errors) if/else

} else {

	// no $_POST set redirect to index.php
	redirect_to('http://wszechwiedzacy.pl');

}

// return the value to jQuery via ajax to confirm if the login was succesful
echo json_encode($ja);

?>