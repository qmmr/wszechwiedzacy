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
<a name="bottom"></a>

<script src="http://www.google.com/jsapi"></script>
<script>	google.load("jquery", "1.4.2");</script>
    
<!-- Tabs, Tooltip, Scrollable, Overlay, Expose. No jQuery. -->
<script src="<?php echo SITE_URL; ?>js/jquery.tools.min.js"></script>
    
<script>
	// if u want to use both jquerytools and jqueryui you need to load tools first
	google.load("jqueryui", "1.8");
</script>
    
<!-- jQuery validate plugin -->
<script src="http://ajax.microsoft.com/ajax/jquery.validate/1.6/jquery.validate.js"></script>
    
<!-- jQuery ScrollTo -->
<script src="<?php echo SITE_URL; ?>js/jquery.scrollTo-1.4.2-min.js"></script>
<script src="<?php echo SITE_URL; ?>js/jquery.localscroll-1.2.7-min.js"></script>
    
<!-- jQuery dataTables -->
<script src="<?php echo SITE_URL; ?>js/jquery.dataTables.min.js"></script>
    
<!-- nivo slider -->
<script src="<?php echo SITE_URL; ?>js/jquery.nivo.slider.pack.js"></script>
    
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
		    	//echo "alert(wszechwiedzacy.session_pts);";
		    	echo "wszechwiedzacy.ranking.init();";
		    	break;
		    default: break;
		}
	    ?>
	    
	    //alert(pts);
		
		// tutorial jQuery.tools
		$(".slideContainer").scrollable({
									size: 1,
									clickable: true,
									loop: true
									}).navigator();

		// page scroll
		$.localScroll();		
						
	});
	
    <?php if(!TESTING_GROUND) : ?>
        
    // google analytics code
    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    

    try{
	    var pageTracker = _gat._getTracker("UA-7089533-2");
	    pageTracker._trackPageview();
    } catch(err) {}
    
	<?php endif; ?>

</script>
</body>
</html>
<?php  if(isset($database)) {$database->close_connection();} ?>