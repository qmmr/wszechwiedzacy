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
			<button type="button" name="start" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">rozpocznij grę</button>
		</div><!--end of #startWrap -->
		
		<div id="loaderContainer" style="display: none">
		    <div id="outer">
			<div id="inner">
			    <p>Losuję następne pytanie ...</p>
			    <div class="ajaxLoader"></div>
			</div>
		    </div>
		</div><!-- ajaxLoader in special container div to be shown when ajax loads pages  with vertical hack see css -->
		
		<div id="tutorialWrap" class="oknoGry">
		
            <a class="prev browse left resultsBlock">&laquo;</a>
			<div class="slideContainer">            
				
				<div class="pics">
                	
					<div class="item">
						<h2>Wprowadzenie</h2>
                        <h3><span class="wrong">nie</span> dla wszechwiedzących ...</h3>												
					</div>
                    
                    <div class="item">
                        <div style="margin-top: 25px;">
                            <span class="bigSpan">3</span>
                            <h4 class="fltlft">rundy</h4>
                        </div>
                        <div>
                            <span class="bigSpan">3</span>
                            <h4 class="fltlft">szanse</h4>
                        </div>
                        <div>
                            <span class="bigSpan">20</span>
                            <h4 class="fltlft">sekund<span style="font-size: 70px; color: #999;position: absolute; top: -20px;">*</span><span class="bleak">*na odpowiedź</span></h4>
                        </div>                        						
                    </div>
                    
                    <div class="item">
                        <h5 class="center first">odpowiadasz klikając myszką</h5>
                        <h6 class="center" style="color: #a1d700;">albo</h6>
                        <h5 class="center">naciskasz klawisz danej odpowiedzi<span style="font-size: 60px; color: #999;position: absolute;">*</span><br /><span class="bleak">* cyfry po prawej stronie odpowiedzi</span></h5>                        
					</div>
                    
					<div class="item">
						<h4 class="center sml">1 błąd<br /><span class="color wrong">strata szansy</span></h4>
						<h4 class="center sml">3 błędy<br /><span class="color wrong">koniec gry</span></h4>
					</div>
                    
					<div class="item">
                        <p class="center">w menu końcowym możesz:</p>                        
                        <ul>                            
                            <li>sprawdzić błędne odpowiedzi</li>
                            <li>zobaczyć statystyki</li>
                            <li>wpisać się na listę zwycięzców<span>*</span><span class="bleak">* dla zarejestrowanych graczy</span></li>
                            <li>sprawdzić swoją pozycję w rankingu</li>
                        </ul>
                        <button id="tutEnd" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">zaczynajmy!</button>
					</div>                    
					
				</div>                
				
			</div>
            <a class="next browse right resultsBlock">&raquo;</a>
			<div class="navi"></div>
            
			<button id="back" title="powrót do menu" type="button" name="back"></button>
			
		</div><!-- end of #tutorialWrap -->		
		
		<div id="quit_dialog">
		    <p>Czy chcesz przerwać grę?</p>
		</div><!-- end of div quit -->
		
    </div><!-- end of mainContent-->
    <?php require_once(LIB_PATH.DS."footer.php"); ?>