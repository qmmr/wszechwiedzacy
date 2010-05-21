<?php
    // if the user is not logged in
    if( !isset($_SESSION['id']) ) :
?>
<p id="log_head" class="fltrt">    
	<a id="login" class="login_flt" href="#" target="_self">Logowanie</a>
	<span class="login_flt"> | </span>
	<a id="register" class="login_flt" href="#">Rejestracja</a>    
</p>

<div id="login_dialog">
    <form id="login_form" method="post" action="">
	<label for="login_email">Email:</label>
	<input id="login_email" class="required" name="email" type="text" value="" />
	<label for="login_password">Hasło:</label>
	<input id="login_password" class="required" name="password" type="password" value="" />
    </form>
    <div class="ajaxLoader" style="display: none"></div>
    <a href="#" id="changeDialog" class="dialogLink" title="Nie masz konta? Zarejestruj się za darmo!">Nowy użytkownik? Zarejestruj się!</a>
    <a href="#" id="forgot" class="dialogLink" title="Odzyskaj hasło, nowe hasło wyślemy na adres mailowy.">Nie pamiętasz hasła?</a>
</div>

<div id="register_dialog">
    <form id="register_form" method="post" action="">
		<label for="reg_username">Nick:</label>
		<input type="text" id="reg_username" class="required" name="reg_username" maxlength="30" value=""  />
		<label for="reg_password">Hasło:</label>
		<input id="reg_password" class="required" name="reg_password" type="password" value="" />
		<label for="reg_email">Email:</label>
		<input id="reg_email" class="required" name="email" type="text" value="" />
    </form>
    <div class="ajaxLoader" style="display: none"></div>
</div>

<div id="forgot_dialog">
    <form id="forgot_form" method="post" action="">
	<label for="lost_email">Email:</label>
	<input id="lost_email" class="required" name="email" title="Podaj adres email" type="text" value="" />
	<p class="info">Podaj adres email na który zarejestrowane zostało konto w serwisie.</p>
    </form>
    <div class="ajaxLoader" style="display: none"></div>
</div>
<?php else : ?>
<p id="reg_head" class="fltrt">    
	<span class="login_flt"><?php echo $session->username; ?></span>
	<?php if($_SESSION['admin_rights'] == 1) : ?>
	<span class="login_flt">|</span>
	<a class="login_flt" href="<?php echo SITE_URL; ?>admin" target="_self">admin</a>
	<?php endif; ?>
	<span class="login_flt">|</span>
	<a href="<?php echo SITE_URL; ?>gracze" target="_self" class="login_flt">moje konto</a>
	<span class="login_flt">|</span>
	<a id="logout" class="login_flt" href="#">wyloguj się</a>
</p>
<?php endif; ?>