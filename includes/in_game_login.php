<?php
require_once("initialize.php");

$perc = round(($_SESSION['odp_poprawne'] * 100) / $_SESSION['ilosc_odp']);	// percentage of correct answers
$score = $_SESSION['zdobyte_punkty'];
$time = $_SESSION['question_start_time'] - $_SESSION['game_start_time'];

if(isset($_SESSION['username'])) {

	$username = $_SESSION['username'];
	
	if($score != 0 && $score >= $btm) {
		
		$id = User::insert_new_score($score, $username, $time, $perc, $_SESSION['odp_poprawne'], $_SESSION['hashed_email']);
		User::update_user_stats($username);
		
	}	

}

// selects top 50 scores
$r = User::find_top_scores(50);
// finds the position based on the points player scored
$key = array_search($score, $r) + 1;
?>
<div class="badge">
    <h1>Gratulacje <?php echo $username; ?>!</h1>
    <h1 class="punkty"><?php echo $score; ?>
        <span><?php echo polOdmiana('punkt', $score); ?></span>
    </h1>
</div>
<button  id="save" title="menu główne" name="save" type="button" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all menu boxShadow">powrót do menu</button>
<script>
$(function(){
	$("#position").text("Miejsce: <?php echo $key;?>");
});
</script>