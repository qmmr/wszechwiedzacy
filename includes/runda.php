<?php
require_once("initialize.php");
$score = $_SESSION['zdobyte_punkty'];
$time = (time() - $_SESSION['game_start_time']);
if(isset($_SESSION['username'])) {
	$username = $_SESSION['username'];
} else {
	// if user is not logged in
	$username = "anonim";
}
// percentage of correct answers
$perc = round(($_SESSION['odp_poprawne'] * 100) / $_SESSION['ilosc_odp']);
// number of chances (stars) to show
$chances = $_SESSION['chances'];
$quote_text = array(
							array(
									"Co to chwilowy spadek formy?",
									"To jeszcze nie koniec, ale ...",
									"Dajesz radę ... ledwo co :)",
									"Masz rację, to nie koniec świata!"
									),
							array(
									"Jedno potknięcie, można wybaczyć.",
									"Spokojnie, każdemu może się zdarzyć",
									"Nie ma powodów do paniki.",
									"Jeden missclick?"
									),
							array(
									"WOW! Czapki z głów przed ",
									"No nieźle, nieźle, nie najgorzej ",
									"Imponujące, doprawdy imponujące ",
									"Ta runda poszła jak z płatka.",
									"Bez zająknięcia, tak trzymaj!",
									"To była rozgrzewka, teraz będzie ciekawiej :)",
									"Gratulacje, fantastyczny wynik "
									)
							);
$n = count( $quote_text[$chances-1] ) - 1;
$chosenText = $quote_text[$chances-1][mt_rand( 0, $n )];

$r = s2m($time);
//print_r($r);
$czas = ($r['min'] != 0) ? ileMinut($r['min']) : false;
$czas .= " " . ileSekund($r['sec']);
?>
<div id="breakWrap" class="oknoGry">
	<h3 class="gra first">"<?php echo $chosenText; ?>"</h3>
	<h3 class="gra">		
		Punkty: <?php echo $score; ?><br />
		Czas: <?php echo $czas; ?><br />
		Procent: <?php echo $perc; ?>% (<?php echo $_SESSION['odp_poprawne']; ?>/<?php echo $_SESSION['question_number']; ?>)
	</h3>
	<h3 class="gra szanse">
		Ilość szans: 
		<?php
			switch($chances) {
				case 3:
					echo "<span id='img1' class='chances'></span><span id='img2' class='chances'></span><span id='img3' class='chances'></span>";
					break;
				case 2:
					echo "<span id='img1' class='chances'></span><span id='img2' class='chances'></span><span id='img3' class='bad'></span>";
					break;
				case 1:
					echo "<span id='img1' class='chances'></span><span id='img2' class='bad'></span><span id='img3' class='bad'></span>";
					break;
				default:
					break;
			}
		?>
	</h3>
	<button id="continue" name="continue" type="button" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">runda <?php echo $_SESSION['current_round']; ?></button>	
</div><!-- end of #breakWrap -->
<?php
// clears the array with questions that were shown in previous round
unset($_SESSION['q_array']);
?>