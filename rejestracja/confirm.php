<?php

    require_once ('../includes/initialize.php');
    
    // here is stored the message to be displayed to the user
    $action = array();
    $action['txt'] = null;
    
    //check if the $_GET variables are present
    if(!empty($_GET['email']) && !empty($_GET['key'])) {
	
	$email = $db->escape_value($_GET['email']);
	$key = $db->escape_value($_GET['key']);
	$query = "SELECT * FROM confirm WHERE email = '{$email}' AND confirm_key = '{$key}' LIMIT 1";
	
	$result = mysql_query($query);
	if(mysql_num_rows($result) == 1) {
	    
	    // update the user database and delete the confirm row
	    // grabs the data from $result
	    $confirm_info = mysql_fetch_assoc($result);
	    
	    // updates the users
	    $query = "UPDATE users SET active = 1 WHERE id = '{$confirm_info[user_id]}' LIMIT 1";
	    
	    $update_user = mysql_query($query);
	    
	    // deletes the confirm record
	    $delete = mysql_query("DELETE FROM confirm WHERE id = '{$confirm_info[id]}' LIMIT 1");
	    if($update_user) {
		
		$action['txt'] = "confirmed";
		
	    } else {
		
		$action['txt'] = "<p>Nie udało się aktualizować użytkownika. Powód: " . mysql_error() . "</p>";
		
	    }
	    
	} else {
	    
	    $action['txt'] = "<div class=\"div_error ui-state-error ui-corner-all\"><p>Adres email lub klucz jest niepoprawny.</p><p><a href=\"http://wszechwiedzacy.pl/kontakt\">zgłoś błąd</a></p></div>";
	    
	}
	
    } else {
	
	// either email or key is empty
	$action['txt'] = "<div class=\"div_error ui-state-error ui-corner-all\"><p>Nie mogę potwierdzić rejestracji gdyż brakuje adresu email lub klucza.</p><p><a href=\"http://wszechwiedzacy.pl/kontakt\">zgłoś błąd</a></p></div>";
	
    }
    
?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Potwierdzenie rejestracji w serwisie wszechwiedzacy.pl</title>
    <link href="http://wszechwiedzacy.pl/stylesheets/default.css" rel="stylesheet" type="text/css" media="screen" />

    <script src="http://www.google.com/jsapi" type="text/javascript"></script>
    <script type="text/javascript">
	   google.load("jquery", "1.4.2");
    </script>
</head>
<body id="default">
    <div id="wrapper">
	<div id="container">
	    <?php
		if($action['txt'] == "confirmed") {
		    // display msg when user confirmed account
	    ?>
	    <div class="div_highlight ui-state-highlight ui-corner-all">
		<p>Twoje konto jest już aktywne, za chwilę zostaniesz przeniesiony na stronę główną, gdzie możesz zalogować się używając swojego adresu email i hasła.<br />Jeśli nie chcesz czekać lub automatyczne przekierowanie nie działa <a href="http://wszechwiedzacy.pl">kliknij tutaj.</a></p>
	    </div>
	    <?php
		} else {
		    // error
		    echo $action['txt'];
		}
	    ?>
	</div>
    </div>
</body>
	<script type="text/javascript">
	    $(window).load (function() {
		var cd = setTimeout(function(){
		    window.location = "http://wszechwiedzacy.pl";
		}, 5000);
	    });
	</script>
</html>