# gdrcd-elections
Elezioni Gdrcd / Elections for Gdrcd


Title: Patch per GDRCD - Sistema di elezioni
Author: Stone / Pedrak (Alessandro Pietrantonio)
Website: http://www.stonewebdesign.it
Version: 1.0.0

Stone / Pedrak  Copyright (c) 2015 L'uso di questo script è libero senza alcuna restrizione

Prima di modificare, sostituire, ecc si consiglia sempre di eseguire una copia di backup dei file


--- ISTRUZIONI PER L'INSTALLAZIONE ---

0- Questa patch necessita l'installazione di GDRCD

1- Estrarre i file gestione_elezioni.inc.php e servizi_elezioni.inc.php nella cartella di GDRCD chiamata "pages"

2- Importate nel database il file elezioni.sql (creerà le 3 tabelle necessarie per usare il sistema di elezioni: elections, candidates, voters)

3- Aprite il file add_to_config.txt e copiate le 6 stringhe di codice nel file config.inc.php come da istruzioni:

	$PARAMETERS['administration']['elezioni']['text']='Gestione Elezioni';
	$PARAMETERS['administration']['elezioni']['url']='main.php?page=gestione_elezioni';
	$PARAMETERS['administration']['elezioni']['access_level']=SUPERUSER;

queste tre andranno sotto PANNELLO GESTIONE

	$PARAMETERS['office']['elezioni']['text']='Elezioni';
	$PARAMETERS['office']['elezioni']['url']='main.php?page=servizi_elezioni';
    $PARAMETERS['office']['elezioni']['access_level']=USER;

queste altre andranno sotto PANNELLO SERVIZI

-- FINE

Se tutto è andato a buon fine sotto la voce di menu "Gestione" avrete la pagina per la gestione delle elezioni, sotto la voce "Servizi" avrete le elezioni vere e proprie.
Dalla gestione potrete creare elezioni con una data di scadenza e gestirne i candidati.
Dal servizio elezioni gli utenti potranno votare (una sola volta a testa per elezione) oppure osservare i risultati in tempo reale o le elezioni passate.
