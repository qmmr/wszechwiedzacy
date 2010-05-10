<?php
class Session
{
	private $logged_in = false;
	public $user_id;
	public $username;
	public $hashed_email;
	public $last_activity;
	public $admin_rights;
	public $start_time;
	public $game_start_time;
	public $question_start_time;
	public $question_number;
	
	function __construct() {
		session_start();
		$this->check_login();
		if($this->logged_in) {
			// here u can do sth when user is logged in
		} else {
			// here u can do sth else when user is logged out
		}
	}
	
	// this is private, only the class itself can use it
	private function check_login() {
		if ( isset($_SESSION['id']) ) {
			$this->user_id = $_SESSION['id'];
			$this->username = $_SESSION['username'];
			$this->logged_in = true;
			$this->admin_rights = $_SESSION['admin_rights'];
			$this->hashed_email = $_SESSION['hashed_email'];
		} else {
			unset( $this->user_id );
			unset( $this->username);
			$this->logged_in = false;
		} // end of isset
	} // end of check_login
	
	public function is_logged_in() {
		return $this->logged_in;
	}
	
	public function login( $user ) {
		if ( $user ) {
			$time = date("Y-m-j H:i:s", time());
			$_SESSION['last_activity'] = $time;
			User::update_last_activity($user->user_name);
			$this->user_id = $_SESSION['id'] = $user->id;
			$this->username = $_SESSION['username'] = $user->user_name;
			$this->hashed_email = $_SESSION['hashed_email'] = md5($user->email);
			$this->admin_rights = $_SESSION['admin_rights'] = $user->admin;
			$this->last_activity =  $user->last_activity;
			$this->logged_in = true;
		} // end of if($user)
	} // end of login
	
	public function logout() {
		unset( $_SESSION['id'] );
		unset( $this->user_id );
		// unsets all variables by setting empty array
		$_SESSION = array();
		$this->logged_in = false;
	} // end of logout

	public function odpowiedz( $poprawna, $pts = 0 ) {
		
		if($poprawna) {
			
			$_SESSION['odp_poprawne']++;
			$_SESSION['zdobyte_punkty'] += $pts;
			
		} else {
			
			$_SESSION['odp_bledne']++;
			$_SESSION['chances']--;
			
		}
		
		$_SESSION['ilosc_odp']++;
		
	}

	// clears session variables
	public function clear_stats() {
		//$_SESSION = array();
		unset($_SESSION['q_array']);
		unset($_SESSION['wrong_answers']);	// array where wrong answers are kept
		$_SESSION['current_round'] = 1;
		$_SESSION['odp_poprawne'] = 0;
		$_SESSION['odp_bledne'] = 0;
		$_SESSION['ilosc_odp'] = 0;
		$_SESSION['question_number'] = 0;
		$_SESSION['chances'] = 3;
		$_SESSION['zdobyte_punkty'] = 0;
		$_SESSION['game_start_time'] = 0;
		$_SESSION['question_start_time'] = 0;
		unset($_SESSION['current_question']);
		unset($_SESSION['first_wrong']);
		unset($_SESSION['second_wrong']);
		unset($_SESSION['third_wrong']);
	}
}

$session = new Session();

// end of session class