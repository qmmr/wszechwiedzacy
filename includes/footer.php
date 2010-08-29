	<div class="clearfloat"></div>

	<div id="footer">

	    <p class="fltlft">
		<?php
		    // bla = bottom links array
		    $bla = array('regulamin' => 'regulamin', 'polityka_prywatnosci' => 'polityka prywatności', 'pomoc' => 'pomoc', 'kontakt' => 'kontakt');

		    foreach ($bla as $link => $name) {
		    	echo "<a title=\"$name\" class=\"$name\" href=" . SITE_URL . $link . ">";
				echo $name;
				echo "</a>";
		    }
		?>
	    </p>
	    <p class="fltrt">projekt i realizacja <a href="http://qmmr.pl" title="Kontakt">qmmr</a> © <?php  echo "2009 - " . date('Y', time()) ?></p>
	    <div class="clearfloat"></div>

	</div><!-- end #footer -->	

    </div><!-- end #container -->

</div><!-- end #wrapper -->
<a id="bottom"></a>

<!--
<div id="faq">
	<a href="#">F.A.Q</a>
</div>
-->


    
<!-- jQuery validate plugin -->
<script src="http://ajax.microsoft.com/ajax/jquery.validate/1.6/jquery.validate.js"></script>

<?php if(in_array($thisPage, array('ranking','default','regulamin','polityka_prywatnosci','pomoc'))):?>
<!-- jQuery ScrollTo -->
<script src="<?php echo SITE_URL; ?>js/jquery.scrollTo-1.4.2-min.js"></script>
<script src="<?php echo SITE_URL; ?>js/jquery.localscroll-1.2.7-min.js"></script>
<script>
$(function(){
	$.localScroll();
});
</script>
<?php endif;?>

<?php if($thisPage == 'default'):?>
<!-- jQuery dataTables -->
<script src="<?php echo SITE_URL; ?>js/jquery.dataTables.min.js"></script>
<?php endif;?>

<?php if($thisPage == 'wszechwiedzacy'):?>
<!-- nivo slider -->
<script src="<?php echo SITE_URL; ?>js/jquery.nivo.slider.pack.js"></script>
<script>
$(window).load(function () {
    // this loads after all elements on the page has been downloaded
    $('#slider').nivoSlider({
        pauseTime: 7500
    });
});
</script>
<?php endif;?>
    
<!-- my own jQuery functions for wszechwiedzacy -->
<script src="<?php echo SITE_URL; ?>js/wszechwiedzacy.js"></script>

<script>
	$(function(){
	    wszechwiedzacy.init();
	    <?php
		// loads different js objects from wszechwiedzacy object
		switch($php_location) {
			case "/kontakt/index.php": echo "wszechwiedzacy.kontakt.init();"; break;
		    case "/gra/index.php": 	echo "wszechwiedzacy.gra.init();"; break;
		    case "/gracze/index.php": echo "wszechwiedzacy.twojaStrona.init();"; break;
		    case "/admin/index.php": echo "wszechwiedzacy.admin.init();"; break;
		    case "/ranking/index.php":
		    	echo (isset($_SESSION['zdobyte_punkty'])) ? "wszechwiedzacy.session_pts = {$_SESSION['zdobyte_punkty']};" : false;
		    	echo "wszechwiedzacy.ranking.init();";
		    	break;
		    default: break;
		}
	    ?>
	    <?php if($thisPage == 'gra'):?>

		$(".slideContainer").scrollable({
    		size: 1,
    		loop: false
		}).navigator();
        
		<?php endif; ?>						
	});	
    <?php if(!TESTING_GROUND) : ?>        
    // google analytics code

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-7089533-4']);
    _gaq.push(['_trackPageview']);
    
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();


	<?php endif; ?>
</script>
</body>
</html>
<?php  if(isset($database)) {$database->close_connection();} ?>