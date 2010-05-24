<?php

require_once("initialize.php");

// here we record the beginning time of the question and if statement that checks if game_time was set/is eqal to 0
$time = time();
$_SESSION['question_start_time'] = $time;
if((isset($_SESSION['game_start_time']) && $_SESSION['game_start_time'] == 0) || !isset($_SESSION['game_start_time'])) {
	$_SESSION['game_start_time'] = $time;
}

$current_round_questions = Question::find_all_by_round();	// finds all question by current round
$num_questions = count($current_round_questions);	// how many questions do we have in the current round
$q_ids = array();	// array in which we're gonna store id's of questions
foreach($current_round_questions as $question) {
	$q_ids[] = $question->id;
}

$shown_questions = array();	// this array contain numbers of questions that were shown

// if we have array in session we assign it to the array $shown_questions
isset($_SESSION['q_array']) ? $shown_questions = $_SESSION['q_array'] : false;

$question_number = rand(1, ($num_questions - 1));	// we randomly pick question

// function checks if this question number is in the $shown_questions array
function check_question ($q, $a) {
	return in_array($q, $a);
}

// while expression is true, meaning we already chosen this question, we pick another one until the statement is false
while(check_question($question_number, $shown_questions)) {
	$question_number = rand(1, ($num_questions - 1));	// we pick random number from all questions
}

$shown_questions[] = $question_number;	// here we put the question into array so that we know which questions were shown previously
sort($shown_questions);

//echo "ilość pytań: " . $num_questions . " ";
//echo "wylosowany numer: " . $question_number . " ";
//echo "shown_questions: " . count($shown_questions) . " ";

// we store the array of shown questions in the session so that it can be retrieved when this pages loads next time
$_SESSION['q_array'] = $shown_questions;

//print_r( $_SESSION['q_array'] );

// adds to the total number of questions
isset( $_SESSION['question_number'] ) ? $_SESSION['question_number'] ++ : $_SESSION['question_number'] = 0;

// display question
$question = $current_round_questions[$question_number];

// new array that holds q&a that are stored in session
// at the end of the game they are available to show ($_SESSION['current_question'])
$cq = array(
				    "tresc" => $question->tresc,
				    "odp_a" => $question->odpowiedz_a,
				    "odp_b" => $question->odpowiedz_b,
				    "odp_c" => $question->odpowiedz_c,
				    "odp_d" => $question->odpowiedz_d,
				    "poprawna" => $question->poprawna,
				    "link" => $question->link
				);
$_SESSION['current_question'] = $cq;
$secret = md5($question->poprawna);

// updates database sets number of started quizes ++
($_SESSION['question_number'] == 1) ? User::start_game($_SESSION['username']) : false;

?>
<div id="pytanieWrap" class="oknoGry">
	<div class="floatContainer">
		<p class="runda">Runda: <?php echo $_SESSION['current_round']; ?></p>
		<p class="numerPytania">Pytanie numer: <?php  echo $_SESSION['question_number']; ?></p>
		<p class="kategoria">Kategoria: <?php echo $question->kategoria; ?></p>
	</div><!-- end of .floatContainer -->
	
	<div class="floatContainer">
		<p class="punkty fltlft">Punkty: <?php  echo $_SESSION['zdobyte_punkty']; ?></p>
		<div class="stars fltlft">
		<p class="szanse fltlft">Szanse:</p>
		<?php
		if($_SESSION['chances'] == 3) {
		
			echo "<span class=\"chances\"></span>";
			echo "<span class=\"chances\"></span>";
			echo "<span class=\"chances\"></span>";
			echo "<div class=\"clearfloat\"></div>";
		
		} elseif ($_SESSION['chances'] == 2) {
		
			echo "<span class=\"chances\"></span>";
			echo "<span class=\"chances\"></span>";
			echo "<span class=\"bad\"></span>";
			echo "<div class=\"clearfloat\"></div>";
		
		} else {
		
			echo "<span class=\"chances\"></span>";
			echo "<span class=\"bad\"></span>";
			echo "<span class=\"bad\"></span>";
			echo "<div class=\"clearfloat\"></div>";
		
		}
		?>
	</div>
	<div class="fltlft">
		<p class="szanse fltlft">Czas:</p>
		<div id="progressbar"></div>
	</div>
	<div class="clearfloat"></div>
	</div><!-- /floatContainer -->
	<div class="clearfloat"></div>
	
	<div id="tresc">
		<p class="pytanie"><?php echo $question->tresc; ?></p>
	</div>
	
	<p class="wybor">Wybierz odpowiedź:</p>
	<form id="odpowiedzi" method="post">
		<div class="fltlft">
			<p class="answer">
				<label class="radio_empty" for="odp_a"><?php echo "$question->odpowiedz_a"; ?><span>1</span></label>
				<input class="radioHidden" id="odp_a" type="radio" name="group" value="<?php echo "$question->odpowiedz_a"; ?>" />
			</p>
		
			<p class="answer">
				<label class="radio_empty" for="odp_b"><?php echo "$question->odpowiedz_b"; ?><span>2</span></label>
				<input class="radioHidden" id="odp_b" type="radio" name="group" value="<?php echo "$question->odpowiedz_b"; ?>" />
			</p>
			<span id="tick"></span>
		</div>
		
		<div class="fltlftZero">
			<p class="answer">
				<label class="radio_empty" for="odp_c"><?php echo "$question->odpowiedz_c"; ?><span>3</span></label>
				<input class="radioHidden" id="odp_c" type="radio" name="group" value="<?php echo "$question->odpowiedz_c"; ?>" />
			</p>
			
			<p class="answer">
				<label class="radio_empty" for="odp_d"><?php echo "$question->odpowiedz_d"; ?><span>4</span></label>
				<input class="radioHidden" id="odp_d" type="radio" name="group" value="<?php echo "$question->odpowiedz_d"; ?>" />
			</p>
		</div><!-- end of .fltlftZero -->
		<div class="clearfloat"></div>
		
		<input type="hidden" name="poprawna" value="<?php echo "$secret"; ?>" />
		<input type="hidden" id="scored" name="score" value="0" />
		
		<div class="center_div">
			<button id="submitButton" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all menu" type="submit" name="submit">Zatwierdź</button>
		</div><!-- end .center_div -->
	</form><!-- #odpowiedzi -->
</div>