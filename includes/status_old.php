<?php
    // if the user is not logged in
    if( !isset($_SESSION['id']) ) {
?>
<div id="log_reg_head" class="float_right">
    <div class="fltrt">
	<a id="login" class="login_flt" href="#" target="_self">Logowanie</a>
	<p class="login_flt"> | </p>
	<a id="register" class="login_flt" href="#">Rejestracja</a>
    </div>
</div>

<div id="login_dialog">
    <form id="login_form" method="post" action="">
	<label for="login_email">Email:</label>
	<input id="login_email" class="required" name="email" type="text" value="" />
	<label for="login_password">Hasło:</label>
	<input id="login_password" class="required" name="password" type="password" value="" />
    </form>
    <div class="ajaxLoader" style="display: none"></div>
    <a href="#" id="changeDialog" class="dialogLink" title="Nie masz konta? Zarejestruj się zupełnie za darmo.">Nowy użytkownik? Zarejestruj się!</a>
    <a href="#" id="forgot" class="dialogLink" title="Odzyskaj hasło, nowe hasło wyślemy na adres mailowy.">Nie pamiętasz hasła?</a>
</div>

<div id="register_dialog">
    <form id="register_form" method="post" action="">
	<label for="reg_username">Nick:</label>
	<input type="text" id="reg_username" class="required" name="reg_username" maxlength="30" value=""  />
	<label for="reg_password">Hasło:</label>
	<input id="reg_password" class="required" name="reg_password" type="password" value="" />
	<label for="reg_password2">Powtórz hasło:</label>
	<input id="reg_password2" class="required" name="reg_password2" type="password" value="" />
	<label for="reg_email">Email:</label>
	<input id="reg_email" class="required" name="email" type="text" value="" />
	<label>Data urodzenia:</label>
	<select id="day" class="required" name="day" title="dzień">
	    <?php
		for($iii = 0; $iii < 32; $iii++ ){
		    if($iii == 0){
			echo "<option value=\"\"></option>";
		    } else if($iii > 0 && $iii < 10) {
			echo "<option value=\"0{$iii}\">".$iii."</option>";
		    } else {
			echo "<option value=\"{$iii}\">".$iii."</option>";
		    }
		}
	    ?>
	</select>
	<select id="month" class="required" name="month" title="miesiąc">
	    <?php
		$months = array("","styczeń","luty","marzec","kwiecień","maj","czerwiec","lipiec","sierpień","wrzesień","październik","listopad","grudzień");
		for($ii = 0; $ii < 13; $ii++ ){
		    if($ii == 0){
			echo "<option value=\"\"></option>";
		    } else if($ii > 0 && $ii < 10) {
			echo "<option value=\"0".$ii."\">".$months[$ii]."</option>";
		    } else {
			echo "<option value=\"".$ii."\">".$months[$ii]."</option>";
		    }
		}
	    ?>
	</select>
	<select id="year" class="required" name="year" title="rok">
	    <?php
		for($i = 1900; $i < 2010; $i++ ){
		    if($i == 1900){
			echo "<option value=\"\"></option>";
		    } else {
			echo "<option value=\"{$i}\">".$i."</option>";
		    }
		}
	    ?>
	</select>
	<div class="clearfloat"></div>
	<p>
	    <input id="akc_regulamin" name="akc_regulamin" class="chkbox fltlft required" type="checkbox" />
	    <label class="display_inline fltlft regulamin" for="akc_regulamin">Przeczytałem i akceptuję <a class="regulamin" href="http://wszechwiedzacy.com/regulamin" target="_blank">regulamin</a></label>
	</p>
	<p class="last">
	    <input id="lista_mailingowa" name="lista_mailingowa" title="Akceptacja regulaminu" class="chkbox fltlft" type="checkbox" />
	    <label class="display_inline fltlft regulamin" for="lista_mailingowa">Chcę otrzymywać wiadomości o nowościach w serwisie</label>
	</p>
	<div class="clearfloat"></div>
	<label>Potwierdź, że jesteś człowiekiem</label>
	<div class="reCaptcha_element">
	    <?php
		// recaptcha
		require_once('recaptchalib.php');
		$publickey = "6Le7VAwAAAAAAEYdqoJIR2-7AqdTa-CtvFV0zqxl";	// you got this from the signup page
		echo recaptcha_get_html($publickey);
	    ?>
	</div>
    </form>
    <div class="ajaxLoader" style="display: none"></div>
</div>

<div id="forgot_dialog">
    <form id="forgot_form" method="post" action="">
	<label for="lost_email">Email:</label>
	<input id="lost_email" class="required" name="email" title="Podaj adres email" type="text" value="" />
	<p class="info">Podaj adres email na który zarejestrowane zostało konto w serwisie, a my wyślemy Ci nowe hasło.</p>
    </form>
    <div class="ajaxLoader" style="display: none"></div>
</div>
<?php
    // this part is shown when user is logged in
    } else {
?>
<div id="log_reg_head" class="float_right">
    <div class="float_right">
		<p class="float_left">Witaj, <a href="<?php echo SITE_URL; ?>gracze" target="_self"><?php echo $session->username; ?></a></p>
		<?php
		    if($_SESSION['admin_rights'] == 1) {
		?>
		<p class="login_flt">|</p>
		<p class="login_flt">
		    <a class="fltlft" href="<?php echo SITE_URL; ?>admin" target="_self">admin panel</a>
		</p>
		<?php } // end of admin conditional ?>
		<p class="login_flt">|</p>
		<p class="login_flt">
		    <a href="<?php echo SITE_URL; ?>gracze" target="_self">moje konto</a>
		</p>
		<p class="login_flt">|</p>
		<a id="logout" class="login_flt" href="#">wyloguj się</a>
    </div>
</div>
<?php
    } // end of checking if user is logged in
?>