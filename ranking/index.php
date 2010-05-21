<?php
    require_once("../includes/initialize.php");
    $num = 50;
    $players = User::find_top($num);
?>
<?php  include_once(LIB_PATH.DS."header.php"); ?>
    <div id="mainContent">
	<table id="rankingGraczy" border="0" cellpadding="0" cellspacing="0" summary="Wyniki <?php echo $num; ?> najlepszych graczy.">
	    <caption>Ranking wszechwiedzących</caption>
	    <thead>
		<tr>
		    <th scope="col" class="pozycja" abbr="pozycja">#</th>
		    <th colspan="2" scope="col" class="nick" abbr="nick">Nick</th>
		    <th scope="col" class="punkty" abbr="punkty">Punkty</th>
		    <th scope="col" class="czas" abbr="czas">Czas</th>
		    <th scope="col" class="poprawne" abbr="poprawne">Odp. pop.</th>
		    <th scope="col" class="procent" abbr="procent">%</th>
		</tr>
	    </thead>
	    <tbody>
		<?php
		    $i = 1;;
		    foreach($players as $player) {
    			echo "<tr title=\"{$i}\" id=\"{$player->id}\"";
    			echo " class=\"";
    			echo (isset($last_id) && ($last_id == $player->id)) ? "newRecord " : false;
    			echo ($i % 2 != 0)  ? "odd" : "even";
    			echo "\">";			
    			echo "<td class=\"position\">{$i}</td>";
    			$grav = GravatarClass::get_gravatar_hashed($player->hashed_email,30); // small gravatar
    			echo "<td class=\"gravatar\" title=\"gravatar\"><a href=\"http://pl.gravatar.com\" target=\"_blank\"><img src=\"".$grav."\" /></a></td>";
    			echo "<td class=\"tla\" title=\"{$player->user_name}\">";
    			//if(User::find_user($player->user_name)) {
//    				
//    				echo "<a href=\"" . SITE_URL . "gracze?nick=" . urlencode($player->user_name);
//    				//echo "&gravatar=" . urlencode($lg = GravatarClass::get_gravatar_hashed($player->hashed_email, 120)); // large gravatar for profile page
//    				echo "\" target=\"_self\">{$player->user_name}</a></td>";
//    				
//    			}
                echo $player->user_name . "</td>";		
    			echo "<td title=\"points\">{$player->points}</td>";
    			echo "<td title=\"czas\">{$player->czas} sek.</td>";
    			echo "<td title=\"poprawne\">{$player->poprawne} </td>";
    			echo "<td title=\"procent\">{$player->procent} </td>";
    			$i++;
		    };
		?>
	    </tbody>
	</table>
    </div>
<?php include_once(LIB_PATH.DS."footer.php"); ?>