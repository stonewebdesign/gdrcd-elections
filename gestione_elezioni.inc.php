<div class="pagina_gestione_abilita">
<?php /*HELP: */ 

/*Controllo permessi utente*/
if ($_SESSION['permessi']<MODERATOR){
    echo '<div class="error">'.gdrcd_filter('out',$MESSAGE['error']['not_allowed']).'</div>';
} else { ?>

<!-- Titolo della pagina -->
<div class="page_title">
   <h2>Gestione elezioni</h2>
</div>

<!-- Corpo della pagina -->
<div class="page_body">

<!-- INIZIO GESTIONE ELEZIONI -->
  
<?php /*Inserimento di una nuova elezione*/
    if ($_POST['op']=='insert') {  
	   /*Eseguo l'inserimento*/
	   $query="INSERT INTO elections (nome_ele, scadenza_ele, naz_ele) VALUES ('".gdrcd_filter('in',$_POST['nome_ele'])."', '".gdrcd_filter('num',$_POST['year'])."-".gdrcd_filter('num',$_POST['month'])."-".gdrcd_filter('num',$_POST['day'])."', '".gdrcd_filter('in',$_SESSION['id_naz'])."')";
	   gdrcd_query($query, 'result');
	   ?>       
	   <div class="warning">
		  <?php echo gdrcd_filter('out',$MESSAGE['warning']['inserted']);?>
	   </div>
	   <!-- Link di ritorno alla visualizzazione di base -->
	   <div class="link_back">
          <a href="main.php?page=gestione_elezioni">
		     <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['plot']['link']['back']); ?>
		  </a>
       </div>
<?php } ?>
<!--Cancellazione elezione-->
<?php /* Prima di cancellare chiedo se si è sicuri */
    if ($_POST['op']=='erase'){ ?>
    	<div class="warning">
			  Sei sicuro di voler cancellare?
		</div>
		<form action="main.php?page=gestione_elezioni"
	      method="post">
	    <input type="hidden"
			         name="id_record"
					 value="<?php echo gdrcd_filter('num',$_POST['id_record']); ?>">
		<input type="hidden" name="op" value="erase_si">
		<input type="submit" value="Sì">
		</form>
		<a href="main.php?page=gestione_elezioni">
		<input type="submit" value="No">
		</a>
	<?php } ?>
		<!--Se si è scelto sì cancello l'elezione-->
		<?php if ($_POST['op']=='erase_si'){
		   /*Eseguo la cancellazione*/
		   $query_ele="DELETE FROM elections WHERE elections.id_ele=".gdrcd_filter('num', $_POST['id_record'])."";
		   gdrcd_query($query_ele, 'result'); 
		   $query_cand="DELETE FROM candidates WHERE candidates.elezione_cand=".gdrcd_filter('num', $_POST['id_record'])."";
		   gdrcd_query($query_cand, 'result');
		   $query_vot="DELETE FROM voters WHERE voters.elezione_vot=".gdrcd_filter('num', $_POST['id_record'])."";
		   gdrcd_query($query_vot, 'result');
		   ?>
	   	   <div class="warning">
			  <?php echo gdrcd_filter('out',$MESSAGE['warning']['deleted']);?>
		   </div>
		   <!-- Link di ritorno alla visualizzazione di base -->
		   <div class="link_back">
	          <a href="main.php?page=gestione_elezioni">
			     <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['news']['link']['back']); ?>
		      </a>
	       </div>
	    <?php } ?>
<!--Fine cancellazione elezione-->

<?php /*Modifica di un elezione*/
	if (gdrcd_filter('get',$_POST['op'])=='doedit'){
	   /*Eseguo l'aggiornamento*/
	   gdrcd_query("UPDATE elections SET nome_ele ='".gdrcd_filter('in',$_POST['nome_ele'])."', scadenza_ele = '".gdrcd_filter('num',$_POST['year'])."-".gdrcd_filter('num',$_POST['month'])."-".gdrcd_filter('num',$_POST['day'])."' WHERE id_ele = ".gdrcd_filter('num',$_POST['id_record'])." LIMIT 1");
?>
   	   <div class="warning">
		  <?php echo gdrcd_filter('out',$MESSAGE['warning']['modified']);?>
	   </div>
	   <!-- Link di ritorno alla visualizzazione di base -->
	   <div class="link_back">
           <a href="main.php?page=gestione_elezioni">
		      <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['news']['link']['back']); ?>
		   </a>
       </div>
<?php } ?>

<?php /*Form di inserimento/modifica elezioni*/
	if ((gdrcd_filter('get',$_POST['op'])=='edit') || 
		(gdrcd_filter('get',$_REQUEST['op'])=='new')){ 
	  /*Preseleziono l'operazione di inserimento*/
	  $operation='insert';
	  /*Se è stata richiesta una modifica*/
	  if ($_POST['op']=='edit'){
		 /*Carico il record da modificare*/
		 $loaded_record=gdrcd_query("SELECT * FROM elections WHERE id_ele=".gdrcd_filter('num',$_POST['id_record'])." LIMIT 1 ");
		 /*Cambio l'operazione in modifica*/
		 $operation='edit';
	  }	?>
    <!-- Form di inserimento/modifica elezioni -->
    <div class="panels_box">
    <form action="main.php?page=gestione_elezioni"
	      method="post"
		  class="form_gestione">

		  <div class='form_label'>
             Nome elezione
          </div>
          <div class='form_field'>
	         <input name="nome_ele"
			        value="<?php echo gdrcd_filter('out',$loaded_record['nome_ele']); ?>" />
		  </div>

		  <div class='form_label'>
              <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['locations']['expiration_date']); ?>
           </div>
		   <div class='form_field'>
	          <?php /* Processo la data di scadenza dell'elezione' */
			     $expiration=explode(" ",$loaded_location['scadenza_ele']);
			     $expiration=explode("-",$expiration[0]);
			  ?>
			  <!-- Giorno -->
			  <select name="day" class="day">
				 <?php for($i=1; $i<=31; $i++){?>
			     <option value="<?php echo $i;?>" <?php if($expiration[2]==$i){echo 'selected';}?>><?php echo $i;?></option>
				 <?php }//for ?> 
			  </select>
			  <!-- Mese -->
		      <select name="month" class="month">
			     <?php for($i=1; $i<=12; $i++){?>
			     <option value="<?php echo $i;?>" <?php if($expiration[1]==$i){echo 'selected';}?>><?php echo $i;?></option>
			     <?php }//for ?> 
			  </select>
			  <!-- Anno -->
			  <select name="year" class="year">
			     <?php for($i=strftime('%Y'); $i<=strftime('%Y')+20; $i++){?>
			     <option value="<?php echo $i;?>" <?php if($expiration[0]==$i){echo 'selected';}?>><?php echo $i;?></option>
			     <?php }//for ?> 
			  </select>
		   </div>

		  <!-- bottoni -->
		  <div class='form_submit'>
			  <?php /* Se l'operazione è una modifica stampo i tasti modifica*/
			        if ($operation == "edit"){?>
			  <input type="submit"
			         value="Modifica elezioni" />
              <input type="hidden"
			         name="id_record"
					 value="<?php echo $loaded_record['id_ele']; ?>">
			  <input type="hidden"
			         name="op"
					 value="doedit">
			  <?php	} /* Altrimenti il tasto inserisci */
					  else { ?>
			  <input type="hidden"
			         name="op"
					 value="insert">
			  <input type="submit"
			         value="Crea nuove elezioni" />
			  <?php	} ?>
		  </div>

	</form>
    </div>
    <!-- Link di ritorno alla visualizzazione di base -->
	<div class="link_back">
         <a href="main.php?page=gestione_elezioni">
		    <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['plot']['link']['back']); ?>
	     </a>
    </div>
<?php }//if ?>

<!-- FINE GESTIONE ELEZIONI -->

<!--GESTIONE CANDIDATI -->

<?php /*Inserimento di un nuovo candidato*/
    if ($_POST['op']=='insert_cand') {  
	   /*Eseguo l'inserimento*/
	   
	   $query="INSERT INTO candidates (elezione_cand, nome_cand, partito_cand) VALUES ('".gdrcd_filter('num',$_POST['id_record'])."', '".gdrcd_filter('in',$_POST['nome_cand'])."', '".gdrcd_filter('in',$_POST['partito_cand'])."')";
	   gdrcd_query($query, 'result');
	   ?>       
	   <div class="warning">
		  <?php echo gdrcd_filter('out',$MESSAGE['warning']['inserted']);?>
	   </div>
	   <!-- Link di ritorno alla visualizzazione di base -->
	   <div class="link_back">
          <a href="main.php?page=gestione_elezioni">
		     <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['plot']['link']['back']); ?> 
		  </a>
       </div>
<?php } ?>


<?php /* Prima di cancellare chiedo se si è sicuri */
    if ($_POST['op']=='canc_cand'){ ?>
    	<div class="warning">
			  Sei sicuro di voler cancellare?
		</div>
		<form action="main.php?page=gestione_elezioni"
	      method="post">
	    <input type="hidden"
			         name="id_record"
					 value="<?php echo gdrcd_filter('num',$_POST['id_record']); ?>">
		<input type="hidden" name="op" value="canc_cand_si">
		<input type="submit" value="Sì">
		</form>
		<a href="main.php?page=gestione_elezioni">
		<input type="submit" value="No">
		</a>
	<?php } /* Cancellatura di un candidato */
    if ($_POST['op']=='canc_cand_si'){
	   /*Eseguo la cancellatura*/
	   $query="DELETE FROM candidates WHERE id_cand=".gdrcd_filter('num', $_POST['id_record'])." LIMIT 1";
	   gdrcd_query($query, 'result'); ?>
   	   <div class="warning">
		  <?php echo gdrcd_filter('out',$MESSAGE['warning']['deleted']);?>
	   </div>
	   <!-- Link di ritorno alla visualizzazione di base -->
	   <div class="link_back">
          <a href="main.php?page=gestione_elezioni">
		     <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['news']['link']['back']); ?>
	      </a>
       </div>
<?php } ?>

<?php /*Modifica di un candidato*/
	if (gdrcd_filter('get',$_POST['op'])=='doedit_cand'){
	   /*Eseguo l'aggiornamento*/
	   gdrcd_query("UPDATE candidates SET nome_cand ='".gdrcd_filter('in',$_POST['nome_cand'])."', partito_cand = '".gdrcd_filter('in',$_POST['partito_cand'])."' WHERE id_cand = ".gdrcd_filter('num',$_POST['id_record'])." LIMIT 1");
?>
   	   <div class="warning">
		  <?php echo gdrcd_filter('out',$MESSAGE['warning']['modified']);?>
	   </div>
	   <!-- Link di ritorno alla visualizzazione di base -->
	   <div class="link_back">
           <a href="main.php?page=gestione_elezioni">
		      <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['news']['link']['back']); ?>
		   </a>
       </div>
<?php } ?>

<!--Elenco i candidati dell'elezione scelta-->

<?php 
    if ($_POST['op']=='candidati') {  
	  
	   	$pagebegin=(int)gdrcd_filter('get',$_REQUEST['offset'])*$PARAMETERS['settings']['records_per_page'];
		$pageend=$PARAMETERS['settings']['records_per_page'];
		//Lettura record
	   	$query= "SELECT * FROM candidates WHERE elezione_cand = ".gdrcd_filter('num',$_POST['id_ele'])." ORDER BY nome_cand LIMIT ".$pagebegin.", ".$pageend."";
		$result=gdrcd_query($query, 'result');

	   ?>
	   
	<div class="elenco_record_gestione">
	<?php 

	$id_elezione=gdrcd_filter('num',$_POST['id_ele']);
	echo $id_elezione; 

	?>
	   <table>
	      <!-- Intestazione tabella -->
          <tr>
		     <td class="casella_titolo"><div class="titoli_elenco">Nome</div></td>
		     <td class="casella_titolo"><div class="titoli_elenco">Partito</div></td>
		     <td class="casella_titolo"><div class="titoli_elenco">Voti</div></td>
		  </tr>
		  <!-- Record -->
          <?php while ($row=gdrcd_query($result, 'fetch')){ ?>
	      <tr class="risultati_elenco_record_gestione">
			 <td class="casella_elemento">
			    <div class="elementi_elenco">
				<?php echo gdrcd_filter('out',$row['nome_cand']); ?>
				</div>
			 </td>
			 <td class="casella_elemento">
			    <div class="elementi_elenco">
				<?php echo gdrcd_filter('out',$row['partito_cand']); ?>
				</div>
			 </td>
			 <td class="casella_elemento">
			    <div class="elementi_elenco">
				
				 
				</div>
			 </td>
			 <td class="casella_controlli"><!-- Iconcine dei controlli -->
		        <!-- Modifica -->
				<div class="controlli_elenco">
				<div class="controllo_elenco" >
		           <form class="opzioni_elenco_record_news" action="main.php?page=gestione_elezioni" method="post">
			          <input type="hidden" name="id_record" value="<?php echo $row['id_cand']?>" />
			          <input type="hidden" name="id_ele" value="<?php echo gdrcd_filter('num',$_POST['id_record'])?>" />
                      <input type="hidden" name="op" value="edit_cand" />
					  <input type="image"
				             src="imgs/icons/edit.png"
						     alt="<?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['ops']['edit']); ?>"
						     title="<?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['ops']['edit']); ?>" />
				   </form>
			    </div>
				<!-- Elimina -->
			    <div class="controllo_elenco" >
				   <form class="opzioni_elenco_record_news" action="main.php?page=gestione_elezioni" method="post">
			          <input type="hidden" name="id_record" value="<?php echo $row['id_cand']?>" />
                      <input type="hidden" name="op" value="canc_cand" />
					  <input type="image"
				             src="imgs/icons/erase.png"
						     alt="<?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['ops']['erase']); ?>"
						     title="<?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['ops']['erase']); ?>"/>
			       </form>
			    </div>
			    <!-- Candidati -->
			    <div class="controllo_elenco" >
		           
			    </div>
				</div>
		     </td>
          </tr>
		  <?php } //while ?>
       </table>
   </div>
   						<form class="" action="main.php?page=gestione_elezioni" method="post">
			          <input type="hidden" name="id_ele" value="<?php echo $id_elezione; ?>" />
                      <input type="hidden" name="op" value="new_cand" />
					  <input type="image"
				             src="imgs/icons/attach.png"
						     alt="Nuovo"
						     title="Nuovo" style="width:20px"/> Nuovo candidato
			       </form>

		<!-- Link di ritorno alla visualizzazione di base -->		   
	   <div class="link_back">
          <a href="main.php?page=gestione_elezioni">
		     <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['plot']['link']['back']); ?> 
		  </a>
       </div>
<?php } ?>

<!--fine elenco candidati-->

<?php /*Form di inserimento/modifica candidati*/
	if ((gdrcd_filter('get',$_POST['op'])=='edit_cand') || 
		(gdrcd_filter('get',$_POST['op'])=='new_cand')){ 
	  /*Preseleziono l'operazione di inserimento*/
	  $operation='insert';
	  $loaded_ele=gdrcd_query("SELECT * FROM elections WHERE id_ele=".gdrcd_filter('num',$_POST['id_ele'])." LIMIT 1 ");
	  /*Se è stata richiesta una modifica*/
	  if ($_POST['op']=='edit_cand'){
		 /*Carico il record da modificare*/
		 $loaded_record=gdrcd_query("SELECT * FROM elections JOIN candidates WHERE id_cand=".gdrcd_filter('num',$_POST['id_record'])." LIMIT 1 ");
		 /*Cambio l'operazione in modifica*/
		 $operation='edit';
	  }	?>
    <!-- Form di inserimento/modifica candidati -->
    <div class="panels_box">
    <form action="main.php?page=gestione_elezioni"
	      method="post"
		  class="form_gestione">

		  <?php 
		  if ($operation!='edit') {
		  	//se operazione non è modifica mostro il nome dell'elezione
		  ?>

		  <div class='form_label'>
             Nome elezione scelta
          </div>
          <div class='form_field'>
	         <?php echo gdrcd_filter('out',$loaded_ele['nome_ele']); ?>
		  </div>
		  <?php 
		  } //chiudo if operation non è edit
		  ?>

		  <div class='form_label'>
             Nome candidato
          </div>
          <div class='form_field'>
          	<select name="nome_cand">
          		<?php
          			//Seleziono con una tendina tra tutti i cittadini
	          		$query_select="SELECT nome FROM personaggio";
	          		$result_select=gdrcd_query($query_select, 'result');

	          		while ($row=gdrcd_query($result_select, 'fetch')) {
	          	?>
	          		<!--Se è una modifica carico il candidato scelto-->
	          		<option value="<?php echo gdrcd_filter('out',$row['nome']); ?>" <?php if($loaded_record['nome_cand']==$row['nome']){ echo 'SELECTED'; } ?>><?php echo $row['nome'];  ?></option>
	          	<?php 
	          		} //chiudo while          		
          		?>
          	</select>
	         <!--<input name="nome_cand"
			        value="<?php echo gdrcd_filter('out',$loaded_record['nome_cand']); ?>" />-->
		  </div>

		  <div class='form_label'>
             Nome partito
          </div>
          <div class='form_field'>
	         <input name="partito_cand"
			        value="<?php echo gdrcd_filter('out',$loaded_record['partito_cand']); ?>" />
		  </div>
	  

		  <!-- bottoni -->
		  <div class='form_submit'>
			  <?php /* Se l'operazione è una modifica stampo i tasti modifica*/
			        if ($operation == "edit"){?>
			  <input type="submit"
			         value="Modifica elezioni" />
              <input type="hidden"
			         name="id_record"
					 value="<?php echo $loaded_record['id_cand']; ?>">
			  <input type="hidden"
			         name="op"
					 value="doedit_cand">
			  <?php	} /* Altrimenti il tasto inserisci */
					  else { ?>
			  <input type="hidden"
			         name="op"
					 value="insert_cand">
					 <input type="hidden"
			         name="id_record"
					 value="<?php echo gdrcd_filter('num',$_POST['id_ele']); ?>">
			  <input type="submit"
			         value="Inserisci candidato" />
			  <?php	} ?>
		  </div>

	</form>
    </div>
    <!-- Link di ritorno alla visualizzazione di base -->
	<div class="link_back">
         <a href="main.php?page=gestione_elezioni">
		    <?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['plot']['link']['back']); ?>
	     </a>
    </div>
<?php }//if ?>

<!-- FINE GESTIONE CANDIDATI -->

<!-- INIZIO ELENCO ELEZIONI -->

<?php if (isset($_REQUEST['op'])===FALSE) { /*Elenco record (Visualizzaione di base della pagina)*/
	//Determinazione pagina (paginazione)
    $pagebegin=(int)gdrcd_filter('get',$_REQUEST['offset'])*$PARAMETERS['settings']['records_per_page'];
	$pageend=$PARAMETERS['settings']['records_per_page'];
	//Conteggio record totali
	$query="SELECT COUNT(*) FROM elections";
	$result_globale=gdrcd_query($query, 'result');
	$record_globale=mysql_fetch_array($result_globale);
	$totaleresults=$record_globale['COUNT(*)'];
	//Lettura record
	$query= "SELECT * FROM elections ORDER BY id_ele LIMIT ".$pagebegin.", ".$pageend."";
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
		     <td class="casella_titolo"><div class="titoli_elenco">Candidati</div></td>
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
			 <td class="casella_elemento">
			    <div class="elementi_elenco">
				<?php 
					//Candidati
					$id_ele = $row['id_ele'];
					//Conteggio il numero di candidati
					$numb=gdrcd_query("SELECT COUNT(*) FROM candidates JOIN elections ON id_ele = elezione_cand WHERE id_ele = ".$id_ele."");
					//stampo numero candidati
					echo "N° candidati registrati: " . $numb['COUNT(*)'];
				 ?>
				 
				</div>
			 </td>
			 <td class="casella_controlli"><!-- Iconcine dei controlli -->
		        <!-- Modifica -->
				<div class="controlli_elenco">
				<div class="controllo_elenco" >
		           <form class="opzioni_elenco_record_news" action="main.php?page=gestione_elezioni" method="post">
			          <input type="hidden" name="id_record" value="<?php echo $row['id_ele']?>" />
                      <input type="hidden" name="op" value="edit" />
					  <input type="image"
				             src="imgs/icons/edit.png"
						     alt="<?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['ops']['edit']); ?>"
						     title="<?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['ops']['edit']); ?>" />
				   </form>
			    </div>
				<!-- Elimina -->
			    <div class="controllo_elenco" >
				   <form class="opzioni_elenco_record_news" action="main.php?page=gestione_elezioni" method="post">
			          <input type="hidden" name="id_record" value="<?php echo $row['id_ele']?>" />
                      <input type="hidden" name="op" value="erase" />
					  <input type="image"
				             src="imgs/icons/erase.png"
						     alt="<?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['ops']['erase']); ?>"
						     title="<?php echo gdrcd_filter('out',$MESSAGE['interface']['administration']['ops']['erase']); ?>"/>
			       </form>
			    </div>
			    <!-- Candidati -->
			    <div class="controllo_elenco" >
		           <form class="opzioni_elenco_record_news" action="main.php?page=gestione_elezioni" method="post">
			          <input type="hidden" name="id_ele" value="<?php echo $row['id_ele']?>" />
                      <input type="hidden" name="op" value="candidati" />
					  <input type="image"
				             src="imgs/icons/reply.png"
						     alt="Gestione candidati"
						     title="Gestione candidati" />
				   </form>
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

     <!-- link crea nuovo -->
     <div class="link_back">
        <a href="main.php?page=gestione_elezioni&op=new">
		   Nuove elezioni
		</a>
     </div>

<?php }//else ?>

</div>

<?php }//else (controllo permessi utente) ?>

</div><!--Pagina-->
