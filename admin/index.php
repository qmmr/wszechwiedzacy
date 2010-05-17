<?php
    require_once("../includes/initialize.php");
    if($session->admin_rights != 1) {
        redirect_to('http://wszechwiedzacy.pl');	// if the user is not logged in as admin we redirect him to main page
    }
    $questions = Question::find_all_active(1);	// finds all questions that are set as active/inactive 1/0
    // $questions = Question::questions_by_author( $_SESSION['username'] );
    $categories = Question::find_categories();
    // loops through categories and adds them to array
    $cat_arr = array();
    foreach ( $categories as $cat ) {
	$cat_arr[] = $cat->kategoria; 
    }
    // removes duplicates
    $u_cats = array_unique($cat_arr);
    // sorts array from lowest
    sort($u_cats);
?>
<?php  include_once(LIB_PATH.DS."header.php"); ?>
<div id="mainContent">
    <table id="pytaniaAdmin" cellpadding="0" cellspacing="0" border="0" summary="Edycja pytań gracza">
	<!--<caption>pytania</caption>-->
	<thead>
	    <tr>
		<th scope="col" abbr="" class="numer">#</th>
		<th scope="col" abbr="" class="round">Runda</th>
		<th scope="col" abbr="" class="category">Kategoria</th>
		<th scope="col" abbr="" class="body">Treść</th>
		<th scope="col" abbr="" class="edit">Edytuj</th>
		<th scope="col" abbr="" class="delete">Usuń</th>
	    </tr>
	</thead>
	<tbody>
	    <?php
		$oi = 1;
		foreach ( $questions as $q ) {
		    echo "<tr";
//		    echo ( $oi % 2 == 0 ) ? " class=\"odd\"" : false;
		    echo " id=\"{$q->id}\"";
		    echo ">";
		    echo "<td name=\"numer\">{$oi}</td>";
		    echo "<td name=\"runda\">{$q->runda}</td>";
		    echo "<td class=\"tla\" name=\"kategoria\">{$q->kategoria}</td>";
		    echo "<td class=\"tla\" name=\"tresc\">{$q->tresc}</td>";
		    if($_SESSION['username'] == $q->autor || $_SESSION['username'] == "wszechwiedzacy") {
			// only authors can edit/delete their questions //except wszechwiedzacy who can edit/delete ALL
			echo "<td class=\"edit\"><a id=\"{$q->id}\" class=\"edit\"><span class=\"edit\" title=\"Edytuj pytanie\"></span></a></td>";
			echo "<td class=\"delete\"><a id=\"{$q->id}\" class=\"delete\"><span class=\"delete\" title=\"Skasuj pytanie\"></span></a></td>";
		    } else {
			// others are no allowed to edit/delete no their questions
			echo "<td class=\"editLock\"><a id=\"{$q->id}\" class=\"editLock\"><span class=\"editLock\"  title=\"Brak uprawnień do edycji\"></span></a></td>";
			echo "<td class=\"deleteLock\"><a id=\"{$q->id}\" class=\"deleteLock\"><span class=\"deleteLock\" title=\"Brak uprawnień do usuwania\"></span></a></td>";
		    }
		    echo "</tr>";
		    $oi++;
		}
	    ?>
	</tbody>
    </table>
    
    <div class="addQuestion">
		<a id="newQuestion" class="addQuestion" href="#">
		    Dodaj nowe pytanie
		    <span class="addQuestion"></span>
		</a>
    </div>
    <div class="clearfloat"></div>
    
    <form id="frmQuestionAdd" name="<?php echo $_SESSION['username']; ?>" method="post" action="">
		<fieldset>
		    <div class="fixWidth">
				<label for="runda">Runda</label>
				<select id="runda" class="margin" name="runda">
				    <option value="">wybierz ...</option>
				    <option value="1">1</option>
				    <option value="2">2</option>
				    <option value="3">3</option>
				</select>
		    </div>
		    
		    <div class="fixWidth">				
			    <label for="kategoria">Kategoria</label>
			    <input id="kategoria" title="Wpisz własną kategorię lub wybierz z listy" name="kategoria" type="text" value=""/>
				<label class="joint">lub</label>				
			    <select id="kategoria_select" class="margin" title="Wybierz kategorię z listy" name="kategoria_select">
				<option value="">wybierz ...</option>
				<?php
				    foreach ( $u_cats as $c ) {
					echo "<option>" . $c . "</option>";
				    }
				?>
			    </select>
			    <div class="clear"></div>
		    </div>
		    
		    <div class="fixWidth formBottom">
				<label for="tresc">Treść</label>
                                <textarea id="tresc" class="defaultText" title="... treść ..." cols="" rows="" name="tresc" type="text"></textarea>
		    </div>
		    
		    <div class="fixWidth">
				<label for="odp_a">Odpowiedź #1</label>
				<input id="odp_a" class="defaultText" title="... odpowiedź pierwsza ..." name="odp_a" type="text"/>
		    </div>
		    
		    <div class="fixWidth">
				<label for="odp_b">Odpowiedź #2</label>
				<input id="odp_b" class="defaultText" title="... odpowiedź druga ..." name="odp_b" type="text"/>
		    </div>
		    
		    <div class="fixWidth">
				<label for="odp_c">Odpowiedź #3</label>
				<input id="odp_c" class="defaultText" title="... odpowiedź trzecia ..." name="odp_c" type="text"/>
		    </div>
		    
		    <div class="fixWidth">
				<label for="odp_d">Odpowiedź #4</label>
				<input id="odp_d" class="defaultText" title="... odpowiedź czwarta ..." name="odp_d" type="text"/>
		    </div>
		    
		    <div class="fixWidth">
				<label for="poprawna">Poprawna</label>
				<select id="poprawna" class="margin" name="poprawna">
				    <option value="">wybierz ...</option>
				    <option value="1">1</option>
				    <option value="2">2</option>
				    <option value="3">3</option>
				    <option value="4">4</option>
				</select>
		    </div>
		    
		    <div class="fixWidth formLast">
				<label for="link">Link</label>
				<input id="link" class="longInput" title="link" name="link" type="text" />
		    </div>		    
		</fieldset>
		
		<div id="loaderContainer" style="display: none">
		    <div id="outer">
				<div id="inner">
				    <p>Dodaję pytanie do bazy danych ...</p>
				    <div class="ajaxLoader"></div>
				</div>
		    </div>
		</div><!-- ajaxLoader in special container div to be shown when ajax loads pages  with vertical hack see css -->
		
    </form><!-- end of form for add question -->
    
    <form id="frmQuestionEdit" name="<?php echo $_SESSION['username']; ?>" method="post">
		<fieldset>
		
		    <p class="fixWidth">
				<label for="runda_edit">Runda</label>
				<select id="runda_edit" class="margin" name="runda_edit">
				    <option value="">wybierz ...</option>
				    <option value="1">1</option>
				    <option value="2">2</option>
				    <option value="3">3</option>
				</select>
		    </p>
		    
		    <p class="fixWidth">
				<label for="kategoria_edit">Kategoria</label>
				<input id="kategoria_edit" title="wpisz własną kategorię lub wybierz z listy" name="kategoria_edit" type="text" value=""/>
				<label style="width: auto"> lub </label>
				<select id="kategoria_select_edit" class="margin" title="wybierz kategorię z listy" name="kategoria_select_edit">
				    <option value="">wybierz ...</option>
				    <?php
					foreach ( $u_cats as $c ) {
					    echo "<option>" . $c . "</option>";
					}
				    ?>
				</select>
		    </p>
		    
		    <p class="fixWidth">
				<label for="tresc_edit formBottom">Treść</label>
				<textarea id="tresc_edit" title="Edytuj treść pytania" name="tresc_edit" type="text"></textarea>
		    </p>
		    
		    <p class="fixWidth">
				<label for="odp_a_edit">Odpowiedź #1</label>
				<input id="odp_a_edit" title="Edytuj odpowiedź pierwszą" name="odp_a_edit" type="text"/>
		    </p>
		    
		    <p class="fixWidth">
				<label for="odp_b_edit">Odpowiedź #2</label>
				<input id="odp_b_edit" class="defaultText" title="Edytuj odpowiedź drugą." name="odp_b" type="text"/>
		    </p>
		    
		    <p class="fixWidth">
				<label for="odp_c_edit">Odpowiedź #3</label>
				<input id="odp_c_edit" class="defaultText" title="Edytuj odpowiedź trzecią." name="odp_c" type="text"/>
		    </p>
		    
		    <p class="fixWidth">
				<label for="odp_d_edit">Odpowiedź #4</label>
				<input id="odp_d_edit" class="defaultText" title="Edytuj odpowiedź odpowiedź czwartą." name="odp_d" type="text"/>
		    </p>
		    
		    <p class="fixWidth">
				<label for="poprawna_edit">Poprawna</label>
				<select id="poprawna_edit" class="margin" title="Edytuj odpowiedź poprawną." name="poprawna_edit">
				    <option value="">wybierz ...</option>
				    <option value="1">1</option>
				    <option value="2">2</option>
				    <option value="3">3</option>
				    <option value="4">4</option>
				</select>
		    </p>
		    
		    <p class="fixWidth formLast">
				<label for="link_edit">Link</label>
				<input id="link_edit" class="longInput" title="Zmień link do źródła" name="link_edit" type="text" />
		    </p>
		    
		</fieldset>
		
		<div id="loaderContainer" style="display: none">
		    <div id="outer">
				<div id="inner">
				    <p>Trwa aktualizacja pytania ...</p>
				    <div class="ajaxLoader"></div>
				</div>
		    </div>
		</div><!-- ajaxLoader in special container div to be shown when ajax loads pages  with vertical hack see css -->
		
    </form><!-- end of form for edit question -->
    
    <div id="question_delete">
		<p>Czy jesteś pewien, że chcesz usunąć to pytanie?</p>
		<p class="uwaga">Nie ma możliwości odzyskania skasowanego pytania!</p>
    </div>
    
</div><!-- end of mainContent -->
<?php include_once(LIB_PATH.DS."footer.php"); ?>