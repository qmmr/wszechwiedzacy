<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><head>	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />	<title></title></head><body><?php require_once 'includes/initialize.php';?><?php $rd = mt_rand(1,130);?><?php echo polOdmiana('punkt', $_GET['num']); ?>
<?php
//		$intro_text = array(	
//								array(
//										"one",
//										"two",
//										"three"
//										),
//								array(								
//										"WOW! Czapki z głów!",
//										"No nieźle, nieźle, nie najgorzej!",
//										"Imponujące, doprawdy imponujące!",
//										"Gratulacje, fantastyczny wynik!",
//										"Niesamowity wynik!"
//										)
//								);
//								
////	echo $intro_text[1][1];
//	echo count($intro_text[1]) . "<br>";
//	echo $rand = mt_rand(0, count($intro_text)) . "<br>";
//	echo $intro_text[1][mt_rand(0, count($intro_text[1]))];
?></body></html>