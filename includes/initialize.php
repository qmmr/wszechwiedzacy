<?php
// define core paths -> as absolute paths to ensure that require_once works as expected
// DIRECTORY_SEPARATOR is constant predefined in PHP (\ for windows, / for unix)

($_SERVER['SERVER_NAME'] == "localhost") ? define("TESTING_GROUND", true) : define("TESTING_GROUND", false);
// next we make sure that separator is set ->
// DIRECTORY_SEPARATOR is constant predefined in PHP (\ for windows, / for unix)
defined("DS") ? null : define("DS", "/");
// then we define the root of the files and URL
if(TESTING_GROUND) {
	
	defined("SITE_ROOT") ? null : define("SITE_ROOT", $_SERVER['DOCUMENT_ROOT'] . "wszechwiedzacy/");
	defined("SITE_URL") ? null : define("SITE_URL", "http://" . $_SERVER['HTTP_HOST'] . "/wszechwiedzacy/");
	
	// path to look for include/required files
	$path = 'c:\wamp\www\wszechwiedzacy\includes';
	
	//echo "localhost ROOT " . SITE_ROOT . " and URL " . SITE_URL;
	
} else {
	
	defined("SITE_ROOT") ? null : define("SITE_ROOT", $_SERVER['DOCUMENT_ROOT'] . "/");
	defined("SITE_URL") ? null : define("SITE_URL", "http://" . $_SERVER['HTTP_HOST'] . "/");
	
	// path to look for include/required files
	$path = '/home/wszechwi/public_html/lib';
	//echo "online ROOT " . SITE_ROOT . " and URL " . SITE_URL;
	
}

// add $path to the includ path
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
//echo ini_get('include_path');
//require_once('test.php');

// the link to includes is the root plus the includes folder
defined("LIB_PATH") ? null : define("LIB_PATH", SITE_ROOT."includes");
// first we set config files
require_once(LIB_PATH.DS."config.php");

// next we set up functions to be used
require_once(LIB_PATH.DS."functions.php");

// next core files
require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."database.php");

//require_once(LIB_PATH.DS."DatabaseObject.php");

//next database related classes
require_once(LIB_PATH.DS."question.php");
require_once(LIB_PATH.DS."user.php");

// breadcrumb navigation
include_once(LIB_PATH.DS."class.breadcrumb.inc.php");

// gravatars
include_once(LIB_PATH.DS."GravatarClass.php");
?>