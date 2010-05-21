<?php

class MySQLDatabase {
    private $connection;
    private $magic_quotes_active;
    private $real_escape_strings_exists;
    public $last_query;

    // constructor
    function __construct() {

        // every time this file runs it will open connection with database
        $this->open_connection();

        // we check if the magic quotes and real escape string are working
        $this->magic_quotes_active = get_magic_quotes_gpc();
        $this->real_escape_strings_exists = function_exists("mysql_real_escape_string");

    } // end of __construct

    // establishes connection with mysql database and when successfull it selects database
    private function open_connection() {

        $this->connection = mysql_connect(DB_SERVER,DB_USER,DB_PASS);


        if(!$this->connection) {

            die("Database connection failed: " . mysql_error());

        } else {

            $db_select = mysql_select_db(DB_NAME,$this->connection);
            if(!$db_select) {

                die("Database selection failed: " . mysql_error());

            } else {

                // if we successfully selected the database we may want to enforce UTF8 to ensure that polish characters are properly displayed
                mysql_query('SET NAMES utf8');
                mysql_query('SET CHARACTER_SET utf8_unicode_ci');

            } // end of !@db_select

        } // end of !$this->connection

    } // end of open_connection

    // closes the connection
    public function close_connection() {

        if(isset($this->connection)) {

            mysql_close($this->connection);
            unset($this->connection);

        } // end of isset($this->connection)

    } // end of close_connection

    public function query($sql) {

        $this->last_query = $sql;
        $result = mysql_query($sql,$this->connection);
        // query for the MySQL database uses the confirm_query function
        $this->confirm_query($result);

        return $result;

    } // end of function query

    // strips statements from special characters that are not allowed or dangerous
    public function escape_value($value) {

        if($this->real_escape_strings_exists) {

            // PHP v4.3.0 or higher undo any magic quote effects so mysql_real_escape_string can do the work
            if($this->magic_quotes_active) {

                $value = stripslashes($value);

            }

            $value = mysql_real_escape_string($value);

        } else {

            // before PHP v4.3.0 if magic quotes aren't already on then add slashes manually
            if(!$this->magic_quotes_active) {

                $value = addslashes($value);

            }
            // if magic quotes are active, then the slashes already exist
        }

        return $value;
    }

    // "database-neutral" methods
    public function fetch_array($result_set) {

        return mysql_fetch_array($result_set);

    }

    public function num_rows($result_set) {

        return mysql_num_rows($result_set);

    }

    public function insert_id() {

        // get the last id inserted over the current db connection
        return mysql_insert_id($this->connection);

    }

    public function affected_rows() {

        return mysql_affected_rows($this->connection);

    }

    private function confirm_query($result) {

        // if the $result is false we output the error message
        if(!$result) {

            $output = "<br />Database query failed: " . mysql_error();
            $output .= "<br /><b>Please correct your last SQL query:</b> " . $this->last_query;
            die($output);

        }

    }
}

// instantiates the object
$database = new MySQLDatabase();

// makes the $db same as $database
$db = &$database;

// end of Database class
