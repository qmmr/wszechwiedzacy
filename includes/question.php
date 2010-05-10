<?php
require_once ( LIB_PATH.DS."database.php" );
class Question
{
	public $id;
	public $autor;
	public $data_dodania;
	public $ostatnia_aktualizacja;
	public $runda;
	public $kategoria;
	public $tresc;
	public $odpowiedz_a;
	public $odpowiedz_b;
	public $odpowiedz_c;
	public $odpowiedz_d;
	public $poprawna;
	public $link;
	function __construct() {
		// ???
	}
	
	public static function how_many_questions() {
		// counts questions that are in the database
		global $database;
		$sql = ("SELECT COUNT(*) FROM pytania");
		$count = $database->query( $sql );
		$result = mysql_fetch_row($count);
		//self::$total_questions = $result[0];
	}
	
	public static function find_all() {
		return self::find_by_sql( "SELECT * FROM pytania" );
	}
	
	// select all questions that have been marked as active (meaning they are ready to be shown in the game)
	public static function find_all_active($active = '1') {
		return self::find_by_sql( "SELECT * FROM pytania WHERE active = '{$active}'");
	}
	
	public static function find_all_by_round () {
		// checks if the current_round is set and finds questions based on that number else finds questions from first round
		isset( $_SESSION['current_round'] ) ? $round = $_SESSION['current_round'] : $round = 1;
		return self::find_by_sql( "SELECT * FROM pytania WHERE runda = '{$round}' AND active = 1" );
	}
	
	public static function find_categories() {
		return self::find_by_sql( "SELECT kategoria FROM pytania" );
	}
	
	public static function questions_by_author ( $autor = "admin" ) {
		global $database;
		$sql = "SELECT id, runda, kategoria, tresc, odpowiedz_a, odpowiedz_b, odpowiedz_c, odpowiedz_d, poprawna, komentarz FROM pytania WHERE autor = '{$autor}'";
		$result_array = array();
		$result_set = $database->query( $sql );
		while ($row = $database->fetch_array($result_set)) {
			$result_array[] = $row;
		}
		return $result_array;
	}
	
	public static function find_by_id( $id = 0 ) {
		global $database;
		$result_array = self::find_by_sql( "SELECT id, runda, kategoria, tresc, odpowiedz_a, odpowiedz_b, odpowiedz_c, odpowiedz_d, poprawna, link FROM pytania WHERE id={$id} LIMIT 1" );
		// here we pull only one object (because we explicitly wanted one (LIMIT 1) if it is empty we return false
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function find_by_sql( $sql = "" ) {
		global $database;
		$result_set = $database->query( $sql );
		/**
		 * every query will be processed by the instantiate function
		 */
		$object_array = array();
		while($row = $database->fetch_array($result_set))
		{
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}
	
	private static function instantiate( $record ) {
		// before we start processing we may want to check if the record exists ??? write it yourself !!!
		// we could use = new Question(); but because we do it inside the class we may use expression self();
		$object = new self();
		foreach ( $record as $attribute => $value ) {
			if ( $object->has_attribute($attribute) ) {
				$object->$attribute = $value;
			}
		} // end foreach
		return $object;
	} // end instantiate
	
	private function has_attribute($attribute) {
		// get object vars returns associative array with all attribute (incl. private ones) as the keys and their current values as value
		// !!! this function checks what attributes are on $this instance of the class (including private ones)!!!
		$object_vars = get_object_vars($this);
		// we don't care what is the value, we want to know if it exists (true or false)
		// !!! on return we compare the sent attribute against the object_var if it exists !!!
		return array_key_exists($attribute, $object_vars);
	} // end of has_attribute
	
} // end of Question class