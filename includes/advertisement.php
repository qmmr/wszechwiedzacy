<?php
	
?>
<div class="item">
    <?php if($session->is_logged_in()) : ?>                        
    <h6 style="margin: 100px 0 40px 0;" class="center">Jesteś zalogowany, co ty tutaj robisz?</h6>
    <button name="start" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">idę grać!</button>
    <?php else : ?>
    <p class="center">nie jesteś jeszcze zarejestrowany?</p>
    <button id="reg3" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">zarejestruj się</button>
    <p class="center">rejestracja nie jest obowiązkowa, ale przeczytaj czemu warto:</p>
    <ul class="center">                            
        <li>rejestracja darmowa, prosta i szybka</li>
        <li style="color: #cc0000;">brak reklam!</li>
        <li>wgląd w statystyki</li>
        <li style="color: #cc0000;">jeśli gra Ci się podoba,w ten sposób podziękujesz </li>
        <li>bierzesz udział w comiesięcznym losowaniu nagrod</li>
        <li><a href="#">czytaj więcej</a></li>
    </ul>
    <?php endif; ?>
</div>

<div class="item">
    <h6>do rankingu możesz dostać się jeśli jesteś zarejestrowanym graczem i zdobędziesz więcej punktów niż osoba zajmująca ostatnie miejsce</h6>
	<h6>rejestracja jest darmowa i prosta <span>(czytaj czemu warto się zarejestrować)</span></h6>
    <button id="reg3" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">zarejestruj się</button>
	<h6>po zakończeniu gry masz możliwość analizy błędnych odpowiedzi oraz oglądnięcia swoich statystyk</h6>                        
	<button name="start" class="ui-button ui-state-default, ui-widget-content ui-state-default ui-corner-all boxShadow menu">wszystko wiem, grajmy!</button>
</div>