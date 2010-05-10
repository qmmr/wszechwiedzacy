<?php
    require_once("../includes/initialize.php");
    require_once(LIB_PATH.DS."header.php");
?>
	<div id="mainContent">
	
		<div class="rules">		
			
			<a id="top"></a>
		    <h1>Pomoc / Zasady</h1>
		    <ol class="arabic">
		    	<li><a href="#punkt1">Zasady</a></li>
		    	<li><a href="#punkt2">Punktacja</a></li>
		    	<li><a href="#punkt3">Pytania</a></li>
		    	<li><a href="#punkt4">Wyniki</a></li>
		    	<li><a href="#faq">FAQ / często zadawane pytania</a></li>
		    </ol>
		    
		    <a id="punkt1" class="clear"></a>
		    <h5>Zasady</h5>		    
		    <ol class="arabic">
		    	<li>Celem gry jest odpowiedzenie na jak największą ilość pytań.</li>
		    	<li>Gra składa się z trzech etapów.</li>
		    	<li>Etap pierwszy: pięć łatwych pytań.</li>
		    	<li>Etap drugi: dziesięć pytań o większym stopniu trudności.</li>
		    	<li>Etap trzeci: nieograniczona liczba pytań, trudnych i bardzo trudnych.</li>
		    	<li>Gracz otrzymuje na początku rozgrywki trzy szanse.</li>
		    	<li>Błędna odpowiedź obniża liczbę szans Zawodnika o jeden.</li>
		    	<li>Trzcia błędna odpowiedź kończy grę.</li>
		    </ol>
		    <a href="#top" class="alignright">do góry</a>
		    
		    <a id="punkt2" class="clear"></a>
		    <h5>Punktacja</h5>		    
		    <ol class="arabic">
		    	<li>Ilość zdobytych punktów, zależne jest od czasu w jakim została udzielona odpowiedź.</li>
		    	<li>Maxymalna liczba punktów możliwych do zdobycia za odpowiedź na pytanie wynosi 100.</li>
		    	<li>Nie ma ograniczenia w ilości możliwych do zdobycia punktów.</li>
		    </ol>
		    <a href="#top" class="alignright">do góry</a>
		    
		    <a id="punkt3" class="clear"></a>
		    <h5>Pytania</h5>		    
		    <ol class="arabic">
		    	<li>Tematyka pytań obejmuje zakres wszystkich możliwych zagadnień.</li>
		    	<li>Skala trudności zależy od etapu (łatwy, średni, trudny).</li>
		    	<li>Pytania nie mogą być obraźliwe ani zawierać treści wulgarnych (patrz Regulamin).</li>
		    	<li>Aby móc dodawać i edytować swoje pytania, należy skontaktować się za pomocą <a href="<?php echo SITE_URL; ?>kontakt">formularza kontaktowego</a> lub wysłać email na adres <a href="mailto:kontakt@wszechwiedzacy.pl" target="_blank">kontakt@wszechwiedzacy.pl</a></li>
		    </ol>
		    <a href="#top" class="alignright">do góry</a>
		    
		    <a id="punkt4" class="clear"></a>
		    <h5>Wyniki</h5>		    
		    <ol class="arabic">
		    	<li>Tabela najlepszych wyników dostępna jest na stronie <a href="<?php echo SITE_URL; ?>ranking">ranking</a></li>
		    	<li>Aby wpisać się na listę wszechwiedzących należy spełnić dwa warunki:
			    	<ol class="lower_alpha child">			    		<li>być zarejestrowanym użytkownikiem</li>			    		<li>zdobyć więcej punktów niż gracz będący na ostatnim miejscu tabeli</li>			    	</ol>
		    	</li>
		    	<li>Czy o czymś zapomniałem? Ktoś to czyta w ogóle? :)</li>
		    </ol>
		    <a href="#top" class="alignright">do góry</a>
			
			<h5>FAQ / często zadawane pytania</h5>
			<a id="faq"></a>
			<ol class="arabic">
				<li><a href="#faq1">Dlaczego moja przeglądarka nie jest obsługiwana?</a></li>
				<li><a href="#faq2">Drugie pytanie?</a></li>
			</ol>
			<a href="#top" class="alignright">do góry</a>

			<h4>Odpowiedzi na często zadawane pytania</h4>
			<ol class="arabic">
				<a id="faq1"></a>
				<li>Bardzo nam przykro, jeśli jesteś jedną z osób, która korzysta z przeglądarek firmy Microsoft czyli (nie)sławnego Internet Explorer, prawdopodobnie nie zagrasz w naszym serwisie. W tej chwili jedynie najnowsza wersja IE8 jest zdolna do wyświetlania gry (a i z tym jest wiele problemów). Najprostszym rozwiązaniem jest zainstalowanie jednej z darmowych przeglądarek, które spełniają wszystkie wymogi (poniżej linki):
					<ul class="browsers">
						<li><a href="http://www.mozilla.com/pl/" target="_blank"><span class="browser firefox"></span>Firefox</a></li>
						<li><a href="http://www.google.com/chrome" target="_blank"><span class="browser chrome"></span>Google Chrome</a></li>
						<li><a href="http://www.opera.com/browser/" target="_blank"><span class="browser opera"></span>Opera</a></li>
						<li><a href="http://www.apple.com/safari/" target="_blank"><span class="browser safari"></span>Safari</a></li>
					</ul>
				</li>
				<a href="#faq" class="alignright">powrót do FAQ</a>
				
				<a id="faq2"></a>
				<li>Odpowiedź na drugie pytanie, z tym, że jeszcze nie ma drugiego pytania :)</li>
				<a href="#faq" class="alignright">powrót do FAQ</a>			</ol>
			<div class="clear"></div>
			<a href="#top" class="alignright">do góry</a>
			

			
		</div><!-- end of .rules -->
		
	</div><!-- end of #mainContent -->
<?php require_once(LIB_PATH.DS."footer.php"); ?>