<?php
//	phpinfo();
	phpinfo(32);

	include_once 'includes/initialize.php';
	echo "DS " . DS . "<br />";
	echo "PATH_SEPARATOR " . PATH_SEPARATOR . "<br />";
	echo "DIRECTORY_SEPARATOR " . DIRECTORY_SEPARATOR . "<br />";
	echo "SITE_ROOT " . SITE_ROOT . "<br />";
	echo "DOCUMENT_ROOT " . $_SERVER['DOCUMENT_ROOT'] . "<br />";
	echo $_SERVER['HTTP_HOST'] . "<br />";
	echo SITE_URL;
?>