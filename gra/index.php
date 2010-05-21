<?php
    require_once("../includes/initialize.php");
    // zeroes stats
    $session->clear_stats();
    // starting the game at difficulty round 1
    $_SESSION['current_round'] = 1;	
?>
<?php require_once(LIB_PATH.DS."header.php"); ?>
    <div id="mainContent">
   
		<div id="startWrap" class="oknoGry">
	    	<h2 id="menu" class="resultsBlock roundCorners menu boxShadow">Menu</h2>
			<button id="show_tut" type="button" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">wprowadzenie</button>
			<button id="start" type="button" name="startWrap" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">rozpocznij grę</button>
		</div><!--end of #startWrap -->
		
		<div id="loaderContainer" style="display: none">
		    <div id="outer">
			<div id="inner">
			    <p>Trwa ładowanie strony ...</p>
			    <div class="ajaxLoader"></div>
			</div>
		    </div>
		</div><!-- ajaxLoader in special container div to be shown when ajax loads pages  with vertical hack see css -->
		
		<div id="tutorialWrap" class="oknoGry">
		
			<div class="slideContainer">
				
				<div class="pics">	
					<div class="item">
						<h2>Wprowadzenie</h2>
						<h4 class="text">3 rundy</h4>
						<h4 class="text">pierwsza runda 5 łatwych pytań</h4>
						<h4 class="text">druga runda 10 trudniejszych pytań</h4>
						<h4 class="text">trzecia runda odpowiadasz do końca (Twojego lub pytań)</h4>
						<h4 class="text">ilość zdobywanych punktów zależna od czasu udzielenia odpowiedzi</h4>
						<h4 class="text">na odpowiedź masz 10 sekund</h4>
						<h3 class="center resultsBlock boxShadow roundCorners">dalej &raquo;</h3>
					</div>
					<div class="item">
						<h4 class="text">rozpoczynasz grę z trzema szansami</h4>
						<h4 class="text">udzielenie złej odpowiedzi lub nie zaznaczenie odpowiedzi skutkuje utratą jednej szansy</h4>
						<h4 class="text">utrata trzeciej szansy kończy grę</h4>						
						<h3 class="center resultsBlock boxShadow roundCorners">dalej &raquo;</h3>
					</div>
					<div class="item">
					<h4 class="text">aby dostać się do tabeli wszechwiedzących, musisz zdobyć więcej punktów niż osoba zajmująca ostatnie miejsce</h4>
						<h4 class="text">jeśli nie jesteś zarejestrowany, możesz wpisać swój nick, jeśli masz już konto wynik zostanie zapisany automatycznie</h4>
						<h4 class="text">masz możliwość analizy błędnych odpowiedzi oraz oglądnięcia swoich statystyk</h4>
						<button id="tut_end" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">wszystko wiem, grajmy!</button>						
					</div>
					
				</div>
				
			</div>
			<div class="navi"></div>			
			<button id="back" title="powrót do menu" type="button" name="back"></button>
			
		</div><!-- end of #tutorialWrap -->		
		
		<div id="quit_dialog">
		    <p>Czy chcesz przerwać grę?</p>
		</div><!-- end of div quit -->
		
    </div><!-- end of mainContent-->
    <?php require_once(LIB_PATH.DS."footer.php"); ?>