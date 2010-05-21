<?php

    require_once("initialize.php");
    
    $json_arr = array("email" => "invalid");
    
    if( isset($_POST['email']) ) {
	
		$email = trim($db->escape_value($_POST['email']));
		
		// check email we = wrong email
		$email_check = (isValidEmail($email)) ? true : $json_arr['email'] = "we";
		
		if($email_check) {
		    
		    // when email is correct we check if it is in our database
		    $query = "SELECT id, email FROM users WHERE email = '{$email}' LIMIT 1";
		    
		    $result = $database->query($query);
		    if($database->num_rows($result) == 1) {
			
				// email found so we generate new password, hash it and insert into database		
				$password = genRandStr(6, 8);
				$hp = sha1($password);
				
				$sql = "UPDATE users SET hashed_password = '$hp' WHERE email = '$email' LIMIT 1";
				
				$result = mysql_query($sql);
				
				if($result) {
				    
				    // password succesfully changed and we can send email
				    
				    // includes swift mailer
				    include_once '../lib/swift_required.php';
				    
				    $info = array(
						'email' => $email,
						'password' => $password
				    );
				    
				    //send the email
				    if(send_email($info, 'recover')) {
					
						//email sent
						$json_arr['email'] = "sent";
					
				    } else {
					
						// email was not sent
						$json_arr['email'] = "failed";
					
				    } // end send_email
				    
				} else {
				    
				    // didn't change the password in the database pnu = password not updated
				    $json_arr['email'] = "pnu";
				    
				} // end $result
		
		    } else {
			
				// email is not in the database de = database empty
				$json_arr['email'] = "de";
			
		    } // end of num_rows
		    
		} // end email_check
	
    } else {
	
		// email war not received
		$json_arr['email'] = "nr";
	
    } // end 
    
    // encode array to be read by ajax/jQuery
    echo json_encode($json_arr);
    
    // end of recover_email.php
 ?>