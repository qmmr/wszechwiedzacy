<?php require_once("initialize.php");?>
<p id="reg_head" class="fltrt">    
	<span class="login_flt">Witaj,</span>
	<a href="<?php echo SITE_URL; ?>gracze" target="_self" class="login_flt"><?php echo $session->username; ?></a>
	<?php if($_SESSION['admin_rights'] == 1) : ?>
	<span class="login_flt">|</span>
	<a class="login_flt" href="<?php echo SITE_URL; ?>admin" target="_self">admin panel</a>
	<?php endif; ?>
	<span class="login_flt">|</span>
	<a href="<?php echo SITE_URL; ?>gracze" target="_self" class="login_flt">moje konto</a>
	<span class="login_flt">|</span>
	<a id="logout" class="login_flt" href="#">wyloguj się</a>
</p>