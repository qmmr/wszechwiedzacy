<?php

require_once("initialize.php");

$perc = round(($_SESSION['odp_poprawne'] * 100) / $_SESSION['ilosc_odp']);	// percentage of correct answers
$score = $_SESSION['zdobyte_punkty'];
$time = $_SESSION['question_start_time'] - $_SESSION['game_start_time'];
$last_id = 1;
$logged = false;

// selects top 30 scores (default)
$results = User::find_top_scores(50);
// gets the lowest score in the ranking table	
$btm = end($results);
// sets $beaten so that we know if we beat the last person in the ranking
($score >= $btm) ? $beaten = true : $beaten = false;
//print_r($results);

if(isset($_SESSION['username'])) {

	$username = $_SESSION['username'];
	$logged = true;
	
	if($score != 0 && $score >= $btm) {
		
		$id = User::insert_new_score($score, $username, $time, $perc, $_SESSION['odp_poprawne'], $_SESSION['hashed_email']);
		User::update_user_stats($username);
		
	}	

}

if($beaten) {
	// selects top 30 scores (default)
	$r = User::find_top_scores(50);
	// finds the position based on the points player scored
	$key = array_search($score, $r);
//	echo $key;
}


// checks if player's score is greater than bottom score from the table
($beaten) ? $color = "high" :	$color = "low";

// odmiana sekund i minut
$r = s2m($time);
//print_r($r);
$czas = ($r['min'] != 0) ? ileMinut($r['min']) : false;
$czas .= " " . ileSekund($r['sec']);

?>
<div id="endWrap" class="oknoGry">
	<div id="yourResult" class="resultsBlock roundCorners">
		<?php if($beaten && $logged) :?>
		<span id="position" class="<?php echo $color; ?> miejsce">Miejsce: <?php echo $key+1; ?></span>
		<span class="<?php echo $color; ?> score">Punkty: <?php echo $score; ?></span>
		<span class="tick"></span>
		<?php elseif($beaten && !$logged) : ?>
		<span class="<?php echo $color; ?> score">Zdobytych punktow: <?php echo $score . " " . polOdmiana('punkt',$score); ?></span>
		<span class="tick"></span>
        <?php else : ?>
		<span class="<?php echo $color; ?> miejsce">Aby dostać się na listę zwycięzców trzeba zdobyć conajmniej <?php echo $btm+1; ?> pkt.</span>
		<span class="wa"></span>
		<?php endif; ?>
	</div>
	<h2 id="menu" class="resultsBlock roundCorners boxShadow menu">Menu:</h2>
	<!-- zmo - zobacz moje odpowiedzi zsw - zapisz swój wynik -->
	<button id="wa" name="wa" type="button" rel="wrongAnswers" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">błędne odpowiedzi</button>
	<button id="sts" name="sts" type="button" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">statystki</button>
	<button id="again" name="again" type="button" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">zagraj jeszcze raz</button>
</div><!-- /breakWrap -->
	
<div id="statystyki" class="oknoGry">
	<h3 class="gra not">
		Twój wynik to <?php echo polOdmiana('punkt', $score); ?><br />w czasie <?php echo $czas; ?><br />
		Sprawdź swoją pozycję w <a id="showResults" href="<?php echo SITE_URL; ?>ranking" target="_blank">rankingu</a> zwycięzców.<br />
		Poprawne odpowiedzi: <?php echo $_SESSION['odp_poprawne']; ?><br />
		Procent poprawnych odpowiedzi: <?php echo $perc; ?>%
	</h3>
	<button name="out" type="button" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu" id="out2">powrót do menu</button>
</div><!--end #statystyki-->

<?php if($score != 0 && $score >= $btm) : ?>
<div id="rezultat" class="oknoGry">

	<?php if($logged) :?>
    
	<div class="badge">
        <h1>Gratulacje <?php echo $username; ?>!</h1>
        <h1 class="punkty"><?php echo $score; ?>
            <span><?php echo polOdmiana('punkt', $score); ?></span>
        </h1>
    </div>
	<button  id="save" title="menu główne" name="save" type="button" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all menu boxShadow">powrót do menu</button>
		
	<?php else : ?>
	
	<form id="saveScore" action="" method="post">
        <div class="badge">
            <h1 class="punkty"><?php echo $score; ?>
                <span><?php echo polOdmiana('punkt', $score); ?></span>
            </h1>
        </div>
		<h4>Chcesz wpisać się na listę wszechwiedzących?</h4>
		<button id="log" title="menu główne" name="save" type="button" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all menu boxShadow">zaloguj się</button>
		<h4><span>albo <a id="reg2" href="#">zarejestruj</a> jeśli jeszcze nie masz konta</span></h4>				
		<button id="anuluj" title="anuluj" name="save" type="button" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all"></button>
	</form>
		
	<?php endif; ?>
	
</div>

<?php else: ?>

<div id="rezultat" class="oknoGry">
	<h3 class="gra not">
		Twój wynik: <?php echo $score; ?> punktów.<br />
		Przykro mi, zabrakło Ci <span class=""><?php echo ($btm-$score)+1; ?></span> punktów, aby móc wpisać się na listę zwycięzców.<br />
		Zobacz Swoje błędy i zagraj jeszcze raz!<br />
		Pamiętaj, trening czyni mistrza!
	</h3>		
	<button name="save" type="button" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all menu boxShadow" id="save">powrót do menu</button>	
</div>

<?php endif; ?>

<div id="wrongAnswers" class="oknoGry">
	 
	 <h3 class="gra first">Twoje błędne odpowiedzi:</h3>	
	<div id="accordion">	
		
		<h3><a href="#section1">Pytanie #1</a></h3>
		
		<!-- tooltip1 id matches selector  with id="wrong1" -->
		<div class="ia" id="">	
			<p class="pytanie">Treść:</p>
			<p class="question">"<?php echo (isset($_SESSION['first_wrong'])) ? $_SESSION['first_wrong']['tresc'] : false; ?>"</p>
			<p class="wrong"><span class="wrong"></span> Twoja odpowiedź: <?php echo $_SESSION['first_wrong']['ya']; ?></p>
			<p class="correct"><span class="correct"></span> Poprawna odpowiedź: <?php echo $_SESSION['first_wrong']['poprawna']; ?></p>
			<a target="_blank" class="ui-state-default ui-corner-all boxShadow" href="<?php echo $_SESSION['first_wrong']['link']; ?>">Chcesz wiedzieć więcej?</a>	
		</div><!-- / -->
		
		<h3><a href="#section2">Pytanie #2</a></h3>
		
		<!-- tooltip2 id matches selector  with id="wrong2" -->
		<div class="ia" id="">	
			<p class="pytanie">Treść:</p>
			<p class="question">"<?php echo (isset($_SESSION['second_wrong'])) ? $_SESSION['second_wrong']['tresc'] : false; ?>"</p>
			<p class="wrong"><span class="wrong"></span> Twoja odpowiedź: <?php echo $_SESSION['second_wrong']['ya']; ?></p>
			<p class="correct"><span class="correct"></span> Poprawna odpowiedź: <?php echo $_SESSION['second_wrong']['poprawna']; ?></p>
			<a target="_blank" class="ui-state-default ui-corner-all boxShadow" href="<?php echo $_SESSION['second_wrong']['link']; ?>">Chcesz wiedzieć więcej?</a>	
		</div><!-- /tooltip2 -->
		
		<h3><a href="#section3">Pytanie #3</a></h3>
		
		<!-- tooltip3 id matches selector  with id="wrong3" -->
		<div class="ia" id="">	
			<p class="pytanie">Treść:</p>
			<p class="question">"<?php echo (isset($_SESSION['third_wrong'])) ? $_SESSION['third_wrong']['tresc'] : false; ?>"</p>
			<p class="wrong"><span class="wrong"></span> Twoja odpowiedź: <?php echo $_SESSION['third_wrong']['ya']; ?></p>
			<p class="correct"><span class="correct"></span> Poprawna odpowiedź: <?php echo $_SESSION['third_wrong']['poprawna']; ?></p>
			<a target="_blank" class="ui-state-default ui-corner-all boxShadow" href="<?php echo $_SESSION['third_wrong']['link']; ?>">Chcesz wiedzieć więcej?</a>
		</div><!-- /tooltip3 -->
		
	</div><!-- end of #accordion -->
	
	<button name="out" type="button" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu" id="out">powrót do menu</button>
	
	<script type="text/javascript">
		$(function() {
			<?php
				// assigns php var to javascript
				echo (isset($_SESSION['zdobyte_punkty'])) ? "wszechwiedzacy.session_pts = {$_SESSION['zdobyte_punkty']};" : false;
			?>
			$("#accordion").accordion({
				autoHeight: false,
				navigation: true,
				collapsible: true,
				active: false,
				animated: 'bounceslide'
			});
		});
	</script>		

</div><!-- end #wrongAnswers -->