<?php
// sets default timezone
date_default_timezone_set('Europe/Warsaw');
$mysql_datetime = date("Y-m-j H:i:s", time());
$microtime = microtime();

// this function is run by PHP when the class is not declared at the beggining (if we forgot to make require_once or sth)
function __autoload($class_name) {

	$class_name = strtolower($class_name);
	$path = "LIB_PATH.DS.{$class_name}.php";
	if(file_exists($path)) {

		require_once($path);

	} else {

		die("File {$path} could not be found.");

	}

}

function strip_zeros_from_date( $marked_string = "" ) {

	// first remove the marked zeros
	$no_zeros = str_replace( '*0', '', $marked_string );
	// then remove any remaining marks
	$cleaned_string = str_replace( '*', '', $no_zeros );
	return $cleaned_string;

}

function redirect_to( $location = null ) {

	if ( $location != null ) {

		header( "Location: {$location}" );
		exit;

	}

}

function output_message( $message = "" ) {

	if ( !empty($message) ) {

		return "<p class=\"message\">{$message}</p>";

	} else {

		return "";

	}

}

// ---------------------------------------------
// form fields -------------------------------
// ---------------------------------------------
// checks if required fields were submitted
// NOT USED WITH AJAX/jQuery/json !!!
function check_req_fields($req_fields) {

	$fields_errors = array();
	foreach($req_fields as $field) {

		if (!isset($_POST[$field]) || empty($_POST[$field])) {
			 
			$fields_errors[] = "Nie wypełniłeś pola " . $field;
			 
		}

	}

	return $fields_errors;

}

function check_form_length($lengths, $max = true) {

	$fields_errors = array();

	if($max) {

		foreach($lengths as $field => $max_length) {
			 
			if(strlen(trim($_POST[$field])) > $max_length) {

				$fields_errors[] = translate_field($field) . " jest zbyt długi(e) (max. {$max_length} znaków).";
				//echo "Error max length: {$field}";

			}
			 
		}

	} else {

		foreach($lengths as $field => $min_length) {
			 
			if(strlen(trim($_POST[$field])) < $min_length) {

				$fields_errors[] = translate_field($field) . " jest za krótki(e) (min. {$min_length} znaki).";
				//echo "Error min length: {$field}";

			}
			 
		}

	}

	return $fields_errors;

}

// counting time (sec to minutes and sec)
function conSec2Min( $gst ) {

	$currentTime = time();
	$min = abs(round(($currentTime - $gst) / 60));
	$var["min"] = ( $min );
	$sec = abs(($currentTime - $gst) % 60);
	$var["sec"] = ( $sec );
	return $var;

}

// seconds to minutes to hours
function s2m($seconds) {

	$time['min'] = abs(floor($seconds / 60));
	$time['sec'] = abs($seconds % 60);
	return $time;

}

// odmiana wyrazów sekundy i minuty po polsku
function ileSekund($s) {

	switch($s) {
		case 0: return 0; break;
		case 1: return 1; break;
		case ($s >= 2 && $s <= 4):
		case ($s >= 22 && $s <= 24):
		case ($s >= 32 && $s <= 34):
		case ($s >= 42 && $s <= 44):
		case ($s >= 52 && $s <= 54): return "$s sekundy"; break;
		default: return "$s sekund"; break;
	}

	return -1;

}

function ileMinut($m) {

	switch($m) {
		case 0: return 0; break;
		case 1: return "$m minuta"; break;
		case ($m >= 2 && $m <= 4):
		case ($m >= 22 && $m <= 24):
		case ($m >= 32 && $m <= 34):
		case ($m >= 42 && $m <= 44):
		case ($m >= 52 && $m <= 54): return "$m minuty"; break;
		default: return "$m minut"; break;
	}

	return -1;

}

/*
 jak dawno temu po polskiemu :)
 SPOSÓB UŻYCIA:
 $wynik = getDiff($time);
 $time = czas zwrócony przez funkcję time()
 */
function getMinutes($minut) {

	// j.pol
	switch($minut) {

		case 0: return 0; break;
		case 1: return 1; break;
		case ($minut >= 2 && $minut <= 4):
		case ($minut >= 22 && $minut <= 24):
		case ($minut >= 32 && $minut <= 34):
		case ($minut >= 42 && $minut <= 44):
		case ($minut >= 52 && $minut <= 54): return "$minut minuty temu"; break;
		default: return "$minut minut temu"; break;

	}

	return -1;

}

function getDiff($timestamp) {

	$now = time();

	if ($timestamp > $now) {

		echo 'Podana data nie może być większa od obecnej.'; // tutaj była 'zła data'
		return;

	}

	$diff = $now - $timestamp;
	$minut = floor($diff/60);
	$godzin = floor($minut/60);
	$dni = floor($godzin/24);

	if ($minut <= 60) {

		$res = getMinutes($minut);

		switch($res) {
			 
			case 0: return "przed chwilą";
			case 1: return "minutę temu";
			default: return $res;
			 
		}

	}

	if ($godzin > 6 && $godzin < 24) {

		return "Dzisiaj ".date("H:i:s", $timestamp);

	} elseif ($godzin > 0 && $godzin < 24) {

		$restMinutes = ($minut-(60*$godzin));
		$res = getMinutes($restMinutes);

		if ($godzin == 1) {
			 
			return "Godzinę temu ".$res;
			 
		} else {
			 
			return "$godzin godzin temu ".$res;
			 
		}

	}

	if ($godzin >= 24 && $godzin <= 48) {

		return "Wczoraj ".date("H:i:s", $timestamp);

	}

	switch($dni) {

		case ($dni < 7): return "$dni dni temu, ".date("Y-m-d", $timestamp); break;
		case 7: return "Tydzień temu, ".date("Y-m-d", $timestamp); break;
		case ($dni > 7 && $dni < 14): return "Ponad tydzień temu, ".date("Y-m-d", $timestamp); break;
		case 14: return "Dwa tygodnie temu, ".date("Y-m-d", $timestamp); break;
		case ($dni > 14 && $dni < 30): return "Ponad 2 tygodnie temu, ".date("Y-m-d", $timestamp); break;
		case 30: case 31: return "Miesiąc temu"; break;
		case ($dni > 31): return date("Y-m-d", $timestamp); break;

	}

	return date("Y-m-d", $timestamp);

}

function polOdmiana($word, $num) {
	$a = array('2','3','4');
	$b = array('12','13','14');
	if($num == 1) {
		return $word;
	} elseif(in_array($num, $b) || in_array(($num % 100), $b)){
		return $word . "ów";
	} elseif(in_array($num,$a) || in_array(($num % 10),$a)){
		return $word . "y";
	} else {
		return $word . "ów";
	}
} // end pol odmiana

/**
 * registration / email confirmation templates
 */
function format_email($info, $format, $type) {

	//set the root
	$root = '/home/wszechwi/public_html/rejestracja';

	//grab the type of template content
	if($type == "register") {

		$template = file_get_contents($root . '/register_template.'. $format);
		$template = ereg_replace('{KEY}', $info['key'], $template);
		$template = ereg_replace('{SITEPATH}', 'http://wszechwiedzacy.pl/rejestracja', $template);

	} elseif ($type == "recover") {

		$template = file_get_contents($root . '/recover_template.'. $format);
		$template = ereg_replace('{PASSWORD}', $info['password'], $template);

	} elseif ($type == "aktualizacja") {

		$template = file_get_contents($root . '/aktualizacja_template.'. $format);
        
    } elseif ($type == "reminder") {

		$template = file_get_contents($root . '/reg_reminder.'. $format);
		        
        $template = ereg_replace('{KEY}', $info['key'], $template);
		$template = ereg_replace('{SITEPATH}', 'http://wszechwiedzacy.pl/rejestracja', $template);
        $template = ereg_replace('{DATAR}', $info['reg_date'], $template);
        
	} else {

		$template = file_get_contents($root . '/newsletter_template.'. $format);
        $template = ereg_replace('{TOPPLAYER}', 'Darwin\'s amstaff', $template);

	}

	// replace email and username in all templates
    $template = ereg_replace('{USERNAME}', $info['username'], $template);
	$template = ereg_replace('{EMAIL}', $info['email'], $template);

	//return the html of the template
	return $template;

}

function send_email($info, $type) {

	//format each email
	$body = format_email($info, 'html', $type);
	$body_plain_txt = format_email($info, 'txt', $type);
	//setup the mailer
	$transport = Swift_MailTransport::newInstance();
	$mailer = Swift_Mailer::newInstance($transport);
	$message = Swift_Message::newInstance();
	$message ->setSubject($info['temat']);
	$message ->setFrom(array('kontakt@wszechwiedzacy.pl' => 'wszechwiedzacy.pl'));
	$message ->setTo(array($info['email'] => $info['username']));
	$message ->setBody($body_plain_txt);
	$message ->addPart($body, 'text/html');
	$result = $mailer->send($message);
	return $result;

}

function isValidEmail($email) {

	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);

} // end of isValidEmail

function confirm_query($result_set) {

	if (!$result_set) {

		die("Database query failed: " . mysql_error());

	} // end of !$result_set

} // end of confirm_query

/**
 *	RANDOM PASSWORD / STRING GENERATOR (from php.net)
 */
function genRandStr($minLen, $maxLen, $alphaLower = 1, $alphaUpper = 1, $num = 1, $batch = 1) {

	$alphaLowerArray = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
	$alphaUpperArray = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
	$numArray = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
	if (isset($minLen) && isset($maxLen)) {
		if ($minLen == $maxLen) {
			$strLen = $minLen;
		} else {
			$strLen = rand($minLen, $maxLen);
		}
		$merged = array_merge($alphaLowerArray, $alphaUpperArray, $numArray);
		if ($alphaLower == 1 && $alphaUpper == 1 && $num == 1) {
			$finalArray = array_merge($alphaLowerArray, $alphaUpperArray, $numArray);
		} elseif ($alphaLower == 1 && $alphaUpper == 1 && $num == 0) {
			$finalArray = array_merge($alphaLowerArray, $alphaUpperArray);
		} elseif ($alphaLower == 1 && $alphaUpper == 0 && $num == 1) {
			$finalArray = array_merge($alphaLowerArray, $numArray);
		} elseif ($alphaLower == 0 && $alphaUpper == 1 && $num == 1) {
			$finalArray = array_merge($alphaUpperArray, $numArray);
		} elseif ($alphaLower == 1 && $alphaUpper == 0 && $num == 0) {
			$finalArray = $alphaLowerArray;
		} elseif ($alphaLower == 0 && $alphaUpper == 1 && $num == 0) {
			$finalArray = $alphaUpperArray;
		} elseif ($alphaLower == 0 && $alphaUpper == 0 && $num == 1) {
			$finalArray = $numArray;
		} else {
			return FALSE;
		}
		$count = count($finalArray);
		if ($batch == 1) {
			$str = '';
			$i = 1;
			while ($i <= $strLen) {
				$rand = rand(0, $count);
				$newChar = $finalArray[$rand];
				$str .= $newChar;
				$i++;
			}
			$result = $str;
		} else {
			$j = 1;
			$result = array();
			while ($j <= $batch) {
				$str = '';
				$i = 1;
				while ($i <= $strLen) {
					$rand = rand(0, $count);
					$newChar = $finalArray[$rand];
					$str .= $newChar;
					$i++;
				}
				$result[] = $str;
				$j++;
			}
		}
		return $result;
	}
} // end of genRandStr
?>