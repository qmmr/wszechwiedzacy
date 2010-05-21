<?php
class User{

	public $id;
	public $active;
	public $admin;
	public $user_name;
	public $email;
	public $hashed_password;
	public $subscribed;
	public $birthday;
	public $show_wiek;
	public $sex;
	public $city;
	public $degree;
	public $register_date;
	public $last_activity;
	public $finished_quizes;
	public $started_quizes;
	public $total_points;
	public $most_points;
	public $last_points;
	public $total_questions;
	public $total_correct_answers;
	public $last_number_questions;
	public $last_number_correct;
	public $total_time;
	public $last_time;
	// these are for ranking table via find_top
	public $points;
	public $czas;
	public $procent;
	public $poprawne;
	public $hashed_email;

	public static function find_top($number = 30) {
			
		return self::find_by_sql("SELECT id, user_name, hashed_email, points, czas, poprawne, procent FROM high_scores ORDER BY points DESC LIMIT {$number}");

	}

	public static function find_top_scorers () {

		$sql = "SELECT id, user_name, points FROM high_scores ORDER BY points DESC";
		$results = mysql_query($sql);

		while($row = mysql_fetch_array($results)) {
			$a[] = array($row[0], $row[1], $row[2]);
		}
		return $a;
	}

	public static function find_top_scores($number = 30) {

		$sql = "SELECT points FROM high_scores ORDER BY points DESC LIMIT {$number}";
		$results = mysql_query($sql);

		while($row = mysql_fetch_array($results)) {
				
			$a[] = $row[0];
				
		}
		return $a;

	}

	public static function find_user ( $user ) {
		$query = "SELECT * FROM users WHERE user_name = '{$user}' LIMIT 1";
		$results = self::find_by_sql($query);
		return !empty($results) ? array_shift($results) : false;
	}

	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		// process all the rows from the query
		// if there is a row of data we run the static function called instantiate -> read rest there
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}

	public static function insert_new_score($score, $name, $time, $perc, $correct, $hashed_email) {

		global $database;
		$sql = "INSERT INTO ";
		$sql .= "high_scores ";
		$sql .= "(user_name, hashed_email, points, czas, poprawne, procent) ";
		$sql .= "VALUES (";
		$sql .= "'$name', '$hashed_email', $score, $time, $correct, $perc)";
		$result = $database->query($sql);

		if($result) {
				
			// if the insert is successful return the id of the inserted record
			return $database->insert_id();
				
		}

	}

	public static function authenticate($email = '', $password = '') {

		global $database;
		$email = $database->escape_value($email);
		$password = $database->escape_value($password);
		$hashed_password = sha1($password);

		$sql = "SELECT id, active, admin, user_name, email, last_activity FROM users ";
		$sql .= "WHERE email = '{$email}' ";
		$sql .= "AND hashed_password = '{$hashed_password}' ";
		$sql .= "LIMIT 1";

		// by running the find_by_sql method we catch the results as $result_array
		// at the same time we instantiate the $object with results
		$result_array = self::find_by_sql($sql);
		// as it turns out we receive an array and if it is not empty we pull the first record from it
		// by using the array_shift, but when it comes empty we return false (meaning we didn't find the user)
		return !empty($result_array) ? array_shift($result_array) : false;

	}

	public static function user_exists($username = '') {
		global $database;
		$username = $database->escape_value($username);
		$sql = "SELECT user_name, active FROM users WHERE user_name = '{$username}' LIMIT 1";
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function email_exists($email = '') {
		global $database;
		$email = $database->escape_value($email);
		$sql = "SELECT active, user_name, email FROM users WHERE email = '{$email}' LIMIT 1";
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function update_last_activity ( $username ) {
		global $database;
		global $mysql_datetime;
		$sql = "UPDATE users SET last_activity = '{$mysql_datetime}' WHERE user_name = '{$username}' LIMIT 1";
		$result = $database->query($sql);
	}

	public static function update_user_stats ( $username ) {

		global $database;
		global $mysql_datetime;
		$quiz_time =  $_SESSION['question_start_time'] - $_SESSION['game_start_time'];
		$sql = "UPDATE users SET ";
		$sql .= "last_activity = '{$mysql_datetime}', ";	// updates user activity
		$sql .= "finished_quizes = finished_quizes + 1, ";	// counts number of completed quizes
		$sql .= "total_points = total_points + {$_SESSION['zdobyte_punkty']}, ";	// counts total points in all time quizes of this user
		$sql .= "total_correct_answers = total_correct_answers + {$_SESSION['odp_poprawne']}, ";	// counts total correct answers in all time quizes of this user
		$sql .= "last_points = {$_SESSION['zdobyte_punkty']}, ";	// number of points in the last quiz
		$sql .= "most_points = IF(last_points > most_points, last_points, most_points), ";	// most points all time
		$sql .= "last_number_correct = {$_SESSION['odp_poprawne']}, ";	// number of correct answers in the last quiz
		$sql .= "total_questions = total_questions + {$_SESSION['question_number']}, ";	// total number of questions for this user
		$sql .= "last_number_questions = {$_SESSION['question_number']}, ";	// number of questions for this user in the last quiz
		$sql .= "total_time = total_time + {$quiz_time}, ";	// total time spent on all quizes
		$sql .= "last_time = {$quiz_time} "; // time spent on last quiz
		$sql .= "WHERE user_name = '{$username}' LIMIT 1";
		$result = $database->query($sql);

	}

	public static function start_game($username) {

		global $database;
		$sql = "UPDATE users SET ";
		$sql .= "started_quizes = started_quizes + 1 ";
		$sql .= "WHERE user_name = '{$username}' LIMIT 1";
		$result = $database->query($sql);

	}

	private static function instantiate($record) {
		// Could check that $record exists and is an array
		// create new object (user class)
		$object = new self;
		// Simple, long-form approach:
		// $object->id			= $record['id'];
		// $object->username		= $record['username'];
		// $object->password		= $record['password'];
		// $object->first_name	= $record['first_name'];
		// $object->last_name		= $record['last_name'];
		// More dynamic, short-form approach:
		// the $record is a row (array) from database, every row has $attribute=>$value (ie. $id=>1)
		// so we run has_attribute function on $object which checks if the $object has attribute of this name
		// if true we are assigning $value from $row to this attribute
		foreach($record as $attribute=>$value){
		  if($object->has_attribute($attribute)) {
		  	$object->$attribute = $value;
		  }
		}
		return $object;
	}

	private function has_attribute($attribute) {
		// get_object_vars returns an associative array with all attributes
		// (incl. private ones!) as the keys and their current values as the value
		$object_vars = get_object_vars($this);
		// We don't care about the value, we just want to know if the key exists
		// Will return true or false
		return array_key_exists($attribute, $object_vars);
	}

} // end of User class