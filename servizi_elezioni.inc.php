<div class="pagina_gestione_abilita">
<?php /*HELP: */ 

if (isset($_SESSION['login'])===FALSE){
    echo '<div class="error">'.gdrcd_filter('out',$MESSAGE['error']['unknown_character_sheet']).'</div>';
} else {
	$query_pers = "SELECT * FROM personaggio WHERE personaggio.nome = '".gdrcd_filter('in',$_SESSION['login'])."'";
	$result_pers = gdrcd_query($query_pers, 'result');

?>

<!-- Titolo della pagina -->
<div class="page_title">
   <h2>Servizi elezioni</h2>
</div>

<!-- Corpo della pagina -->
<div class="page_body">


<!--Carico il form per la votazione se op è vota-->

<?php //Aumento di uno secondo la scelta della votazione 

if ((gdrcd_filter('get',$_POST['op'])=='scelta_cand')){

	 $query="INSERT INTO voters (nome_vot, elezione_vot, candidato_vot) VALUES ('".gdrcd_filter('out', $_SESSION['login'])."', '".gdrcd_filter('num', $_POST['id_record'])."', '".gdrcd_filter('num', $_POST['scelta'])."')";
	 gdrcd_query($query, 'result');

	 ?>

	<div class="warning">
		  Grazie per aver votato!
	   </div>
	   <!-- Link di ritorno alla visualizzazione di base -->
	   <div class="link_back">
          <a href="main.php?page=servizi_elezioni">
		     <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['plot']['link']['back']); ?>
		  </a>
       </div>

	<?php
    
    } //chiudo if scelta cand



if ((gdrcd_filter('get',$_POST['op'])=='vota')){

	//Controllo che l'utente abbia la cittadinanza
	$cittadinanza=current_user_cittadinanza();

	if ($cittadinanza != 1) {

		echo '<div class="error">Devi ottenere la cittadinanza per poter votare.</div>';

		?>
		<!-- Link di ritorno alla visualizzazione di base -->
		   <div class="link_back">
	          <a href="main.php?page=servizi_elezioni">
			     <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['plot']['link']['back']); ?>
			  </a>
	       </div>
	    <?php

	} else { //se il personaggio ha cittadinanza lo lascio passare

		/*Controllo che l'utente non abbia già votato*/
		$result_controllo=gdrcd_query("SELECT nome_vot FROM voters WHERE elezione_vot = ".gdrcd_filter('num',$_POST['id_record'])."");
		$nome_vot = $result_controllo['nome_vot'];

		if (gdrcd_filter('in',$_SESSION['login'])==$nome_vot) {

		echo '<div class="error">Hai già votato in questa elezione.</div>';

		?>
		<!-- Link di ritorno alla visualizzazione di base -->
		   <div class="link_back">
	          <a href="main.php?page=servizi_elezioni">
			     <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['plot']['link']['back']); ?>
			  </a>
	       </div>
	    <?php
		} else {

		if ($_POST['op']=='vota'){
			 /*Carico il record da modificare*/
			 $loaded_record=gdrcd_query("SELECT * FROM elections WHERE id_ele=".gdrcd_filter('num',$_POST['id_record'])." LIMIT 1 ");
			 
	    	$query= "SELECT * FROM candidates WHERE elezione_cand = ".gdrcd_filter('num',$_POST['id_record'])."";
			$result=gdrcd_query($query, 'result');
	    
	    } //chiudo if vota

         ?>
<!--Form per votazione-->
         

         <h3><?php echo gdrcd_filter('out',$loaded_record['nome_ele']); ?></h3>

         <?php echo $_REQUEST['pg'] ?>

        <?php while ($row=gdrcd_query($result, 'fetch')){ ?>

        <form action="main.php?page=servizi_elezioni" target="_top" method="post" id="form1" name="form1">

			
        	<input type="hidden" name="scelta" value="<?php echo $row['id_cand']; ?>">
			<input type="hidden" name="id_record" value="<?php echo gdrcd_filter('out',$loaded_record['id_ele']); ?>" />
			<input type="hidden" name="op" value="scelta_cand">
			<input type="submit" name="candidato" value="Vota <?php echo $row['nome_cand']; ?>"><br />
		
		</form>

        <?php
        	
         } //chiusura while ?>
		

	<!-- Link di ritorno alla visualizzazione di base -->		   
	   <div class="link_back">
          <a href="main.php?page=servizi_elezioni">
		     <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['plot']['link']['back']); ?> 
		  </a>
       </div>

<?php
		}//chiudo else già votato
	}//chiudo else cittadinanza
} //chiudo if gdrcd filter vota ?>
<!--fine form votazione -->
 
<!--ELENCO ELEZIONI-->
<?php if (isset($_REQUEST['op'])===FALSE) { /*Elenco record (Visualizzaione di base della pagina)*/
	
	//Determinazione pagina (paginazione)
    $pagebegin=(int)gdrcd_filter('get',$_REQUEST['offset'])*$PARAMETERS['settings']['records_per_page'];
	$pageend=$PARAMETERS['settings']['records_per_page'];
	//Conteggio record totali
	$query="SELECT COUNT(*) FROM elections";
	$result_globale=gdrcd_query($query, 'result');
	$record_globale=mysql_fetch_array($result_globale);
	$totaleresults=$record_globale['COUNT(*)'];
	//Seleziono le elezioni che risultano aperte
	$query= "SELECT * FROM elections WHERE naz_ele = '".gdrcd_filter('in',$_SESSION['id_naz'])."' AND scadenza_ele >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) ORDER BY id_ele LIMIT ".$pagebegin.", ".$pageend."";
	$result=gdrcd_query($query, 'result');
    $numresults=gdrcd_query($result, 'num_rows');


	/* Se esistono record */
	if ($numresults>0){ ?>
       <!-- Elenco dei record paginato -->
       <div class="elenco_record_gestione">
       <table>
	      <!-- Intestazione tabella -->
          <tr>
		     <td class="casella_titolo"><div class="titoli_elenco">Tipo</div></td>
		     <td class="casella_titolo"><div class="titoli_elenco">Data</div></td>
		  </tr>
		  <!-- Record -->
          <?php while ($row=gdrcd_query($result, 'fetch')){ ?>
	      <tr class="risultati_elenco_record_gestione">
			 <td class="casella_elemento">
			    <div class="elementi_elenco">
				<?php echo $row['nome_ele']; ?>
				</div>
			 </td>
			 <td class="casella_elemento">
			    <div class="elementi_elenco">
				<?php echo $row['scadenza_ele']; ?>
				</div>
			 </td>
			 
			 <td class="casella_controlli"><!-- Iconcine dei controlli -->
		        <!-- Modifica -->
				<div class="controlli_elenco">
				<div class="controllo_elenco" >
		           <form class="opzioni_elenco_record_news" action="main.php?page=servizi_elezioni" method="post">
			          <input type="hidden" name="id_record" value="<?php echo $row['id_ele']?>" />
                      <input type="hidden" name="op" value="vota" />
					  <input type="image"
				             src="imgs/icons/edit.png"
						     alt="Vota"
						     title="Vota" />
				   </form>
			    </div>

			    <!--Risultati-->
			    <div class="controllo_elenco" >
		           <form class="opzioni_elenco_record_news" action="main.php?page=servizi_elezioni" method="post">
			          <input type="hidden" name="id_record" value="<?php echo $row['id_ele']?>" />
                      <input type="hidden" name="op" value="risultati" />
					  <input type="image"
				             src="imgs/icons/reply.png"
						     alt="Risultati"
						     title="Risultati" />
				   </form>
			    </div>
				
			    </div>
				</div>
		     </td>
          </tr>
		  <?php } //while ?>
       </table>
       </div>
     <?php }//if ?>

	 <!-- Paginatore elenco -->
	 <div class="pager">
       <?php if($totaleresults>$PARAMETERS['settings']['records_per_page']){
	            echo gdrcd_filter('out',$MESSAGE['interface']['pager']['pages_name']);
		        for($i=0;$i<=floor($totaleresults/$PARAMETERS['settings']['records_per_page']);$i++){
			       if ($i!=gdrcd_filter('num',$_REQUEST['offset'])){?>
                   <a href="main.php?page=gestione_elezioni&offset=<?php echo $i; ?>"><?php echo $i+1; ?></a>
				   <?php } else { echo ' '.($i+1).' '; }
                } //for
             }//if ?>
     </div>

     <!-- Link di ritorno alla visualizzazione di base -->		   
	   <div class="link_back">
          <a href="main.php?page=servizi_elezioni&op=old_ele">
		     Visualizza le elezioni passate 
		  </a>
       </div>

     

<?php }//if elenco record ?>

<!--FINE ELENCO ELEZIONI-->

<!--INIZIO RISULTATI TEMPO REALE-->
<?php
if ($_POST['op']=='risultati'){

	$query="SELECT COUNT(*) FROM voters WHERE elezione_vot = ".gdrcd_filter('num',$_POST['id_record'])."";
	$result_globale=gdrcd_query($query, 'result');
	$record_globale=mysql_fetch_array($result_globale);
	$totaleresults=$record_globale['COUNT(*)'];

	$query = "SELECT * FROM candidates WHERE elezione_cand = ".gdrcd_filter('num',$_POST['id_record'])."";
	$result=gdrcd_query($query, 'result');
	?>

	<div class="elenco_record_gestione">
       <table>
	      <!-- Intestazione tabella -->
          <tr>
		     <td class="casella_titolo"><div class="titoli_elenco">Nome candidato</div></td>
		     <td class="casella_titolo"><div class="titoli_elenco">Voti</div></td>
		  </tr>
		  <!-- Record -->
          <?php while ($row=gdrcd_query($result, 'fetch')){ ?>
	      <tr class="risultati_elenco_record_gestione">
			 <td class="casella_elemento">
			    <div class="elementi_elenco">
				<?php echo $row['nome_cand']; ?>
				</div>
			 </td>
			 <td class="casella_elemento">
			    <div class="elementi_elenco">
				<?php 

					$row_count=gdrcd_query("SELECT COUNT(*) AS stat FROM voters WHERE candidato_vot = ".$row['id_cand']."");

					echo $row_count['stat'];

				 ?>
				</div>
			 </td>
			 
          </tr>
		  <?php } //while ?>
       </table>
       </div>

       <!-- Link di ritorno alla visualizzazione di base -->		   
	   <div class="link_back">
          <a href="main.php?page=servizi_elezioni">
		     <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['plot']['link']['back']); ?> 
		  </a>
       </div>
<?php

} //chiusura if risultati
?>

<!--FINE RISULTATI TEMPO REALE-->

<!--ELENCO VECCHIE ELEZIONI-->
<?php if ($_REQUEST['op']=='old_ele') { /*Elenco record (Visualizzaione di base della pagina)*/
	
	//Determinazione pagina (paginazione)
    $pagebegin=(int)gdrcd_filter('get',$_REQUEST['offset'])*$PARAMETERS['settings']['records_per_page'];
	$pageend=$PARAMETERS['settings']['records_per_page'];
	//Conteggio record totali
	$query="SELECT COUNT(*) FROM elections";
	$result_globale=gdrcd_query($query, 'result');
	$record_globale=mysql_fetch_array($result_globale);
	$totaleresults=$record_globale['COUNT(*)'];
	//Seleziono le elezioni che risultano aperte
	$query= "SELECT * FROM elections WHERE scadenza_ele < DATE_SUB(CURDATE(), INTERVAL 1 DAY) ORDER BY id_ele LIMIT ".$pagebegin.", ".$pageend."";
	$result=gdrcd_query($query, 'result');
    $numresults=gdrcd_query($result, 'num_rows');


	/* Se esistono record */
	if ($numresults>0){ ?>
       <!-- Elenco dei record paginato -->
       <div class="elenco_record_gestione">
       <table>
	      <!-- Intestazione tabella -->
          <tr>
		     <td class="casella_titolo"><div class="titoli_elenco">Tipo</div></td>
		     <td class="casella_titolo"><div class="titoli_elenco">Data</div></td>
		  </tr>
		  <!-- Record -->
          <?php while ($row=gdrcd_query($result, 'fetch')){ ?>
	      <tr class="risultati_elenco_record_gestione">
			 <td class="casella_elemento">
			    <div class="elementi_elenco">
				<?php echo $row['nome_ele']; ?>
				</div>
			 </td>
			 <td class="casella_elemento">
			    <div class="elementi_elenco">
				<?php echo $row['scadenza_ele']; ?>
				</div>
			 </td>
			
          </tr>
		  <?php } //while ?>
       </table>
       </div>
     <?php }//if ?>

	 <!-- Paginatore elenco -->
	 <div class="pager">
       <?php if($totaleresults>$PARAMETERS['settings']['records_per_page']){
	            echo gdrcd_filter('out',$MESSAGE['interface']['pager']['pages_name']);
		        for($i=0;$i<=floor($totaleresults/$PARAMETERS['settings']['records_per_page']);$i++){
			       if ($i!=gdrcd_filter('num',$_REQUEST['offset'])){?>
                   <a href="main.php?page=gestione_elezioni&offset=<?php echo $i; ?>"><?php echo $i+1; ?></a>
				   <?php } else { echo ' '.($i+1).' '; }
                } //for
             }//if ?>
     </div>

     <!-- Link di ritorno alla visualizzazione di base -->		   
	   <div class="link_back">
          <a href="main.php?page=servizi_elezioni">
		     Torna all'elenco delle elezioni
		  </a>
       </div>

     

<?php }//if elenco record ?>

<!--FINE ELENCO VECCHIE ELEZIONI-->

</div>

<?php

} //else personaggio ?>

</div><!--Pagina-->
