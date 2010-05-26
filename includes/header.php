<?php

    if ( isset( $_SESSION['last_activity'] ) ){

		$alt = 60 * 60 * 24; // automatic logout time 24hours
		// by comparing time now and last_activity we know if the user was inactive for time set in $alt
		$td = strtotime( $mysql_datetime ) - strtotime( $_SESSION['last_activity'] );
	
		if ( $td > $alt ) {
	
		    // logout user if he was not active for the last $alt
		    $session -> logout();
	
		}
	
		if ( $td > ( 60 * 10 ) && $td < $alt ){
	
		    // update user activity
		    // echo "user activity updated";
		    //User::update_last_activity( $_SESSION['username'] );
		    $_SESSION['last_activity'] = $mysql_datetime;
	
		} else {
	
		    // echo "time difference = ".$td;
	
		}
	
		// echo "td: ".$td." alt: ".$alt." last_activity: ".$_SESSION['last_activity'];
		
    }

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="Description" content="'Wiesz, czy nie wiesz? oto jest pytanie?' - wszechwiedzacy.pl to gra w przglądarce, w której zawodnik staje przed zadaniem odpowiadania na pytania z najróżniejszych kategorii, sprawdź czy zasługujesz na tytuł wszechwiedzącego!" />
    <title>wszechwiedzacy.pl :: wiesz czy nie wiesz, oto jest pytanie?</title>
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <link href="<?php echo SITE_URL; ?>stylesheets/default.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo SITE_URL; ?>stylesheets/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo SITE_URL; ?>stylesheets/dataTable.css" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>stylesheets/nivo-slider.css" media="all" />
    
    <script src="http://www.google.com/jsapi"></script>
    <script>google.load("jquery", "1.4.2");</script>
    
</head>
<?php

$thisPage = "";
$php_location = $_SERVER['PHP_SELF'];
(TESTING_GROUND) ? $php_location = str_replace("wszechwiedzacy/","",$php_location) : false;
switch($php_location) {
    case "/index.php":$thisPage = "wszechwiedzacy";break;
    case "/gra/index.php":$thisPage = "gra";break;
    case "/ranking/index.php":	$thisPage = "ranking";break;
    case "/kontakt/index.php":$thisPage = "kontakt";break;
    case "/regulamin/index.php":	$thisPage = "regulamin";break;
    case "/polityka_prywatnosci/index.php":$thisPage = "polityka_prywatnosci";break;
    case "/pomoc/index.php":$thisPage = "pomoc";break;
    case "/gracze/index.php":$thisPage = "gracze";break;
    default:$thisPage = "default";break;
}

?>
<body id="<?php echo $thisPage; ?>">
	<a name="top"></a>
    <div id="wrapper">
		<div id="container">
		    <div id="topHeader">
		    
				<a href="<?php echo SITE_URL; ?>" target="_self">
				    <span class="logo fltlft">wszechwiedzacy</span>
				</a>
				
				<ul id="mainNav" class="tabs">
				<?php
				
				if(TESTING_GROUND) {
					
//					localhost name->link array
					$nla = array(
										'strona główna' => 'http://localhost/wszechwiedzacy/',
										'gra' => 'http://localhost/wszechwiedzacy/gra',
										'ranking' => 'http://localhost/wszechwiedzacy/ranking',
										'blog' => 'http://localhost/wordpress/',
										'kontakt' => 'http://localhost/wszechwiedzacy/kontakt'
										);
										
				} else {

					// online
					$nla = array(
										'strona główna' => 'http://wszechwiedzacy.pl',
										'gra' => 'http://wszechwiedzacy.pl/gra',
										'ranking' => 'http://wszechwiedzacy.pl/ranking',
										'blog' => 'http://wszechwiedzacy.pl/blog',
										'kontakt' => 'http://wszechwiedzacy.pl/kontakt'
										);
									
				}
				
				foreach( $nla as $name => $link ){

				    echo "<li ";
				    if ( $php_location == ( "/" . $name . "/index.php" ) ) {

					echo "class=\"current\">";

				    }else if ( $php_location == "/index.php" && $name == "strona główna" ) {

					echo "class=\"current\">";

				    } else {

						if ( $name == "strona główna" ) {

						    echo "class=\"wszechwiedzacy\">";

						} else {

						    echo "class=\"{$name}\">";

						}

				    }

				    if ( $name == "strona główna" ) {
				    	
				    	echo "<a title=\"{$name}\" class=\"{$name}\" href=".$link.">";
				    	
				    } else {
				    	
				    	echo "<a title=\"{$name}\" class=\"{$name}\" href=".$link.">";
				    	
				    }

				    echo "<span>" . $name . "</span></a></li>";

				}
			    ?>
				</ul>
		    </div><!-- end #topHeader -->
		    
		    <div id="header">
		    <!-- no script / javascript turned off msg -->
		    <noscript>Oops! Wygląda na to że Twoja przeglądarka nie ma włączonego javascript'u. Bez niego nie zagrasz we wszechwiedzacy.pl</noscript>
			<a name="crumb"></a>
			<div id="breadcrumb">
			    <p>Jesteś tutaj:
				<?php
				    $breadcrumb = new breadcrumb;
				    $breadcrumb -> showfile = false;
				    $breadcrumb -> homepage = "wszechwiedzacy.pl";
				    // $breadcrumb->linkFile = true;
				    $breadcrumb -> changeFileName = array( '/gracze/index.php' => 'statystyki gracza' );
				    echo $breadcrumb -> show_breadcrumb();
				?>
			    </p>
			</div>
			<?php include_once( LIB_PATH . DS . "status.php" );?><div class="clear_float"></div>
		    </div ><!-- end #header -->