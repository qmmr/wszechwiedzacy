<?php

require_once ("../includes/initialize.php");

// checks if user is logged in
($session->is_logged_in()) ? $logged = true : $logged = false;

$top_scorers = User::find_top_scorers();

// checks if $_GET was set
if(isset($_GET['nick'])) {

    $nick = $_GET['nick'];

    // searches for users place -> returns 0 based position
    $ranking_position = array_search($nick,$top_scorers);
    if($ranking_position === false) {

        // no such user
        //redirect_to("http://wszechwiedzacy.com/ranking");

    }

} else {

    if($logged) {

        $nick = $_SESSION['username'];
        // same as above // fix repeating?
        $ranking_position = array_search($nick,$top_scorers);

    } else {

        //redirect_to("http://wszechwiedzacy.pl/ranking");

    }

} // end of if/else session is logged in

// make the user object that holds all information that is stored in database
$user_exists = User::user_exists($nick);
if($user_exists) {

    $current_user = User::find_user($nick);
    $last = strtotime($current_user->last_activity);

} else {

    $current_user = false;

}

// function displays last activity in polish
$wynik = getDiff($last);

$display_status = "nieaktywny";
if((time() - $last) < 600) {

    $display_status = "aktywny";

}

$sex = "ukryty";
// if sex was chosen
if($current_user->sex != 0) {

    ($current_user->sex == 1) ? $sex = "kobieta":$sex = "mężczyzna";

}

$wiek = "ukryty";
if($current_user->show_wiek != 0) {

    $wiek = date("Y",time()) - date("Y",strtotime($current_user->birthday));

}

$miasto = "ukryte";
if($current_user->city != "") {

    $miasto = $current_user->city;

}

$degree = "ukryte";
if($current_user->degree != "") {

    $degree = $current_user->degree;

}

if(isset($_GET['gravatar'])) {

    $glink = $_GET['gravatar'];

} elseif(isset($_SESSION['hashed_email'])) {

    $glink = GravatarClass::get_gravatar_hashed($_SESSION['hashed_email'],120);

} else {

    $glink = "http://www.gravatar.com/avatar/";

}

$percent = round($current_user->total_correct_answers * 100 / $current_user->total_questions, 0);
$percent_last_game = round($current_user->last_number_correct * 100 / $current_user->last_number_questions, 1);
$average_ppg = round(($current_user->total_points / $current_user->started_quizes), 1);
$average_pps = round($current_user->total_points / $current_user->total_time, 2);

// poziom counting
$level = "";
$stars = 1;
$cufq = $current_user->finished_quizes;

// this switch determines how many stars are shown next to users poziom(level)
switch($cufq) {
    case ($cufq >= 10 && $cufq < 50):
        $level = "kapujący";
        $stars = 2;
        break;
    case ($cufq >= 50 && $cufq < 100):
        $level = "ogarniacz";
        $stars = 3;
        break;
    case ($cufq >= 100 && $cufq < 250):
        $level = "tęgogłowy";
        $stars = 4;
        break;
    case ($cufq >= 250 && $cufq < 500):
        $level = "moozg";
        $stars = 5;
        break;
    case ($cufq >= 500 && $cufq < 1000):
        $level = "przegość";
        $stars = 6;
        break;
    case ($cufq >= 1000):
        $level = "wszechwiedzacy";
        $stars = 7;
        break;
    default:
        $level = "początkujący";
        break;
}

//$mcr = microtime();
//echo $result_microtime = "Page was generated in " . ($mcr - $microtime) . " microseconds";
include_once (LIB_PATH . DS . "header.php");
?>
<div id="mainContent">
    <div class="stats_left fltlft">
	<h1>Profil gracza:</h1>
	<div class="gravatar fltlft">
	    <a href="http://pl.gravatar.com/" target="_blank">
		<img class="avatar" src="<?php echo $glink; ?>" />
	    </a>
	</div>
	<div class="dane">
	    <p class="nick">Nick: <span><?php echo $nick; ?></span></p>
	    <p>Wiek: <?php echo $wiek; ?></p>
	    <p>Płeć: <?php echo $sex; ?></p>
	    <p>Miasto: <?php echo $miasto; ?></p>
	    <p>Wykształcenie: <?php echo $degree; ?></p>
	    <p>Status: <?php echo $display_status; ?></p>
	    <?php if($logged && !isset($_GET['nick'])):?>
	    <p><a id="edytuj_profil" href="">Edytuj swój profil</a></p>
	    <?php endif; ?>
	</div>
	<div class="clearfloat"></div>
    </div>
    <div class="stats_right fltlft">
	<h1>Statystyki:</h1>
	<p class="poziom" title="<?php echo $level; ?>">Poziom:
		<div class="stars">
		<?php

    $show_star = "<span class=\"small_star\"></span>";
    switch($stars) {
        case 2:
            echo $show_star . $show_star;
            break;
        case 3:
            echo $show_star . $show_star . $show_star;
            break;
        case 4:
            echo $show_star . $show_star . $show_star . $show_star;
            break;
        case 5:
            echo $show_star . $show_star . $show_star . $show_star . $show_star;
            break;
        case 6:
            echo $show_star . $show_star . $show_star . $show_star . $show_star . $show_star;
            break;
        case 7:
            echo $show_star . $show_star . $show_star . $show_star . $show_star . $show_star .
                $show_star;
            break;
        default:
            echo $show_star;
            break;
    }
        ?>
		</div>
	</p>
	<p>Ranking: <?php echo ($ranking_position != false) ? ($ranking_position + 1) . " miejsce": "nieklasyfikowany"; ?></p>
	<p>Najlepszy wynik: <?php echo $current_user->most_points; ?></p>
	<p>Suma zdobytych punktów: <?php echo $current_user->total_points; ?></p>
	<p>Procent poprawnych odpowiedzi: <?php echo $percent; ?> %</p>
	<p>Liczba poprawnych odpowiedzi: <?php echo $current_user->total_correct_answers; ?></p>
	<?php if($logged):?>
	<a href="#" id="show_full_stats">pokaż dodatkowe statystyki</a>
	<?php endif; ?>
    </div>
    <div class="clearfloat"></div>
    <div id="full_stats">
	<div class="stats_left fltlft">
	    <h2>Pełne statystyki</h2>
	    <p>Ilość rozpoczętych gier: <?php echo $current_user->number_quizes; ?></p>
	    <p>Ilość ukończonych gier: do zrobienia</p>
	    <p>Ostatni wynik: <?php echo $current_user->last_points; ?></p>
	    <p>Średnia zdobytych punktów w jednej grze: <?php echo $average_ppg; ?> punktów/gra</p>
	    <p>Średnia zdobytych punktów na sekundę: <?php echo $average_pps; ?> punktów/sekundę</p>
	    <p>Całkowita liczba wyświetlonych pytań: <?php echo $current_user->total_questions; ?></p>
	    <p>Czas wszystkich gier: <?php echo $current_user->total_time; ?> sekund</p>
	</div>
	<div class="stats_right fltlft">
	    <h2>Ostatnia gra</h2>
	    <p>Ilość pytań w ostatniej grze: <?php echo $current_user->last_number_questions; ?></p>
	    <p>Ilość poprawnych odpowiedzi w ostatniej grze: <?php echo $current_user->last_number_correct; ?></p>
	    <p>Procent poprawnych odpowiedzi w ostatniej grze: <?php echo $percent_last_game; ?> %</p>
	    <p>Czas ostatniej gry: <?php echo $current_user->last_time; ?> sekund</p>
	</div>
	<div class="clearfloat"></div>
    </div>

    <div id="tabs-3">
		<!--<p>Tutaj coś będzie ...</p>-->
    </div><!-- end of tab 3 -->

    <div id="edit_profile">
    	<form id="profile_form" method="post" action="">
    
    	    <div class="fixWidth">
        		<label for="wiek">Wiek:</label>
        		<input class="checkbox" id="wiek" name="wiek" type="checkbox" <?php echo ($current_user->show_wiek == 1) ? "checked":false; ?> />
        		<label class="info">pokazuj ile masz lat</label>
    	    </div>
    
    	    <div class="fixWidth">
        		<label>Płeć:</label>
        		<label class="gender" for="woman">K</label>
        		<input id="woman" type="radio" name="gender_group" value="1" <?php echo ($current_user->sex == 1) ? "checked":false; ?> />
        		<label class="gender" for="man">M</label>
        		<input id="man" type="radio" name="gender_group" value="2" <?php echo ($current_user->sex == 2) ? "checked":false; ?> />
    	    </div>
    
    	    <div class="fixWidth">
        		<label for="city">Miasto:</label>
        		<input type="text" id="city" name="city" value="<?php echo $current_user->city; ?>" />
    	    </div>
    
    	    <?php $degree_array = array("wybierz","podstawowe","zawodowe","licealne","wyższe licencjackie","wyższe magisterskie"); ?>
    	    <div class="fixWidth">
        		<label for="degree">Wykształcenie:</label>
        		<select id="degree" name="degree" title="wykształcenie">
                <?php
                    for($i = 0; $i <= 5; $i++) {
                        echo "<option ";
                        echo ($current_user->degree == $degree_array[$i]) ? "selected":false;
                        echo ">" . $degree_array[$i] . "</option>";
                    }
                ?>
        		</select>
    	    </div>
    
    	    <div class="fixWidth">
        		<label for="newsletter">Newsletter:</label>
        		<input class="checkbox" id="newsletter" name="newsletter" type="checkbox" <?php echo ($current_user->subscribed == 1) ? "checked":false; ?> />
        		<label class="info">Wiadomości na email?</label>
    	    </div>
    
    	    <h2>Zmiana hasła</h2>
    	    <p>Jeśli nie chcesz zmieniać hasła, pozostaw puste pola</p>
    
    	    <div class="fixWidth">
        		<label for="old_pass">Obecne hasło:</label>
        		<input id="old_pass" name="old_pass" type="password" value="" />
    	    </div>
    
    	    <div class="fixWidth">
        		<label for="new_pass">Nowe hasło:</label>
        		<input id="new_pass" name="new_pass" type="password" value="" />
    	    </div>
    
    	    <div class="fixWidth">
        		<label for="new_pass2">Potwierdź nowe hasło:</label>
        		<input id="new_pass2" name="new_pass2" type="password" value="" />
    	    </div>
    
    	    <input type="hidden" id="userName" value="<?php echo $current_user->user_name; ?>" />
    
    	</form>
        <div class="ajaxLoader" style="display: none"></div>
    </div>

</div><!-- end of mainContent -->
<?php include_once ("../includes/footer.php"); ?>