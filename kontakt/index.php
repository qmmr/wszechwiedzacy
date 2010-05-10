<?php
    require_once("../includes/initialize.php");
?>
<?php  include_once(LIB_PATH.DS."header.php"); ?>
<div id="mainContent">
    <div class="formWrap">
	<form id="frmKontakt" method="post" action="">
	    <fieldset>
		<!--<legend>Czekam na wiadomość od Ciebie!</legend>	-->
		<p class="fixWidth">
		    <label for="kontakt_name">Nick:</label>
		    <input id="kontakt_name" class="required" title="Podaj swój nick" name="name" type="text" />
		</p>
		<p class="fixWidth">
		    <label for="kontakt_email">Email:</label>
		    <input id="kontakt_email" class="required" title="Podaj swój adres email" name="email" type="text" />
		</p>
		<p class="fixWidth">
		    <label for="temat">Temat:</label>
		    <select id="temat" class="margin required" name="temat" title="Wybierz temat">
			<option value="">Wybierz temat</option>
			<option value="1">Odnośnie strony</option>
			<option value="2">Znalazł(em/am) błąd!</option>
			<option value="3">Chęć współpracy</option>
			<option value="4">Zupełnie z innej beczki</option>
		    </select>
		</p>
		<p class="fixWidth formLast">
		    <label for="mainMessage">Wiadomość:</label>
		    <textarea id="mainMessage" name="mainMessage" class="required" title="Napisz wiadomość" cols="45" rows="5"></textarea>
		</p>
		<div class="reCaptcha_element">
		    <?php
			// recaptcha
//			require_once('../includes/recaptchalib.php');
//			$publickey = "6LcCxgoAAAAAAP7KxwWDzko8aosvpEMT6KJyvAwp";	// you got this from the signup page
//			echo recaptcha_get_html($publickey);
		    ?>
		</div>
		<button class="ui-state-default ui-corner-all" type="submit" name="submit" id="kontakt_submit">Wyślij</button>
		<div class="clear_float"></div>
	    </fieldset>
	</form>
    </div><!-- end of formWrap -->
</div><!-- end of mainContent -->
<?php include_once(LIB_PATH.DS."footer.php"); ?>