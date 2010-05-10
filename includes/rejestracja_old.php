<?php

    require_once("initialize.php");
    require_once("recaptchalib.php");
    
    $json_arr = array("user" => "valid", "password" => "valid", "email" => "valid", "mysql" => "valid","recaptcha"=>"none");
    $privatekey = "6Le7VAwAAAAAAOINb5fhPe4tqoi0k0kuPohIVOI8";	// reCaptcha private key
    $resp = null;	// the response from reCAPTCHA
    
    if(isset($_POST["username"])) {
	
	$errors = array();
	$username = trim(mysql_prep($_POST['username']));
	$password = trim(mysql_prep($_POST['password']));
	$password2 = trim(mysql_prep($_POST['password2']));
	$hashed_password = sha1($password);
	$email = trim(mysql_prep($_POST['email']));
	$birthday = trim(mysql_prep($_POST['birthday']));
	$mailing = $_POST['mailing'];
	
	// reCaptcha	
	// was there a reCAPTCHA response?
	if (isset($_POST["recaptcha_response_field"]) && isset($_POST['recaptcha_response_field'])) {
	    
	    $resp = recaptcha_check_answer (
		$privatekey,
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]
	    );
	    if($resp->is_valid) {
		
		// if recaptcha is valid
		$json_arr['recaptcha'] = "valid";
		// check if user name hasn't been taken
		$user_exists = User::user_exists($username);
		if($user_exists) {
		    
		    array_unshift($errors, "username taken");
		    $json_arr['user'] = "taken";
		    
		}
		
		$email_check = (isValidEmail($email)) ? true : $json_arr['email'] = "error";
		
		// check if this email is in the database
		$email_exists = User::email_exists($email);
		if($email_exists) {
		    
		    array_unshift($errors, "email taken");
		    $json_arr['email'] = "taken";
		    
		}
		
		// no errors on first test, next we check if the username and password are of required length
		$fields_max_lengths = array("username" => 30, "password" => 30, "password2" => 30);
		$fields_min_lengths = array("username" => 3, "password" => 4, "password2" => 4);		
		$errors = array_merge($errors, check_form_length($fields_max_lengths, true), check_form_length($fields_min_lengths, false));
		if(empty($errors)) {
		    
		    $time = date("Y-m-j H:i:s", time());
		    $query = "INSERT INTO users ( ";
		    $query .= "user_name, hashed_password, email, register_date, subscribed, birthday ) VALUES (";
		    $query .= " '{$username}', '{$hashed_password}', '{$email}', '{$time}', {$mailing}, '{$birthday}' )";
		    $result = mysql_query($query);
		    if ($result) {
			
			// we are going to send letter with confirmation
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
									'username' => $username,
									'email' => $email,
									'key' => $skey
								    );
			    
			    //send the email
			    if(send_email($info, 'register')) {
				
					//email sent
					$json_arr['email'] = "sent";
				
			    } else {
				
					// email was not sent
					$json_arr['email'] = "error";
				
			    }
			    
			} else {
			    
			    $json_arr['mysql'] = "Oops! Nie mogłem dodać danych do tablicy confirm: " . mysql_error();
			    
			}
			
		    } else {
			
			// error when creating user
			$json_arr['mysql'] = "Błąd podczas rejestracji użytkownika: " . mysql_error();
			
		    } // end of $result if/else
		    
		} else {
		    
		    // notify the user that the nick has been taken
		    // $json_arr['user'] = "name taken";
		    // there were errors in length
		    // $json_arr['password'] = "length";
		    
		} // end of empty($errors) if/else
		
	    } else {
		
		// recaptcha is invalid
		$json_arr['recaptcha'] = "wrong";
		
	    }
	    
	} else {
	    
	    $json_arr['recaptcha'] = "not posted";
	    
	}
	
    } else {
	
        // no $_POST set redirect to index.php
	redirect_to('http://wszechwiedzacy.pl');
	
    }
    
    // return the value to jQuery via ajax to confirm if the login was succesful
    echo json_encode($json_arr);
    
?>