<?php


    $Num_Item = $fileop[0];
    $moment = $fileop[1];
    $Periode = $fileop[2];
    $Num_Jour = $fileop[3];
    $Jour = $fileop[4];
    $Clients = $fileop[5];
    $Num_Projet = $fileop[6];
    $Nb_Heures = $fileop[7];
    $Compte_Rendu=$fileop[8];
    $Libelle_du_Projet = $fileop[9];
    $etat_projet = $fileop[10];
    $Selection = $fileop[11];
    $employe_type = $fileop[12];

    /*
     * VERIFICATION SI LE LE DELAI D'INSERTION N'EST PAS PASSE
     */
    /*$now = new DateTime("now");
    $date  = DateTime::createFromFormat('d/m/y', $dt);
    $aut = false;

    if(intval($now->format('m')) - intval($date->format('m')) == 1){
        if(intval($now->format('d')) <= 10){
            $aut = true;
        }
    }

    if ($now->format('m') == $date->format('m')){
        $aut = true;
    }*/

    $annee = date_parse($moment);
    $year = $annee['year'];

    $phase='00';


    $aff = 1;


    $ordre = 1;

    try{

        $result = $conn->query("SELECT to_date('$moment','mm/dd/rrrr') FROM DUAL");
        $date = $result->fetch();
        $dt = $date[0];

        $result = $conn->query("SELECT to_char( (to_date('$date[0]','dd/mm/rrrr')), 'WW' ) FROM dual");
        $week0= $result->fetch();
        $week = $week0[0];

        /*
         * REMPLACEMENT DU NUMERO PROJET
         */
        $tabs = array();
        $tabs1 = array();
        $nbre = 0;

        $Num_Projet_origine = $Num_Projet;

        $req = $conn->query('SELECT * FROM GRH_AADDA001');
        while ($donnee = $req->fetch()){
            $tabs[$nbre] = $donnee['NO_PROJ'];
            $nbre++;
        }

        $nbre = 0;

        $req = $conn->query('SELECT * FROM GRH_AMIOS001');
        while ($donnee = $req->fetch()){
            $tabs1[$nbre] = $donnee['NO_PROJ'];
            $nbre++;
        }


        if($Num_Projet == "EESPEC001"){
            $Num_Projet = "EESPE001";
        }
        else if ($Num_Projet == "MLDEV0001"){
            $Num_Projet = "MLDEV001";
        }
        else if($Num_Projet == "ML.1508"){
            $Num_Projet = "ML01-1508";
        }
        else if ($Num_Projet == "ML.1709"){
            $Num_Projet = "ML.1801";
        }
        else if(in_array($Num_Projet, $tabs)){
            $Num_Projet = "AADDA001";
        }
        else if(in_array($Num_Projet, $tabs1)){
            $Num_Projet = "AMIOS001";
        }
        /*
         * RECTIFICATION DES NUMEROS PROJETS EMPLOYES TECHNIQUE ET ADMINISTARTION
         */
        $tabAdmin = array();
        $tabsTechnique = array();
        $nbre = 0;
        $req = $conn->query('SELECT * FROM GRH_ADMINISTRATION');
        while ($donnee = $req->fetch()){
            $tabAdmin[$nbre] = $donnee['NO_PROJ'];
            $nbre++;
        }


        $nbre = 0;
        $req = $conn->query('SELECT * FROM GRH_TECHNIQUE');
        while ($donnee = $req->fetch()){
            $tabsTechnique[$nbre] = $donnee['NO_PROJ'];
            $nbre++;
        }

        if(in_array($Num_Projet, $tabAdmin)){
            $req= $conn->query("SELECT * FROM GRH_INTERVENANT WHERE NO_PROJ = '$Num_Projet' AND PHASE = '$phase' AND PERS_ID = '$pers' AND ORDRE = '$ordre' ");
            $donne = $req->fetch();
            if ($donne==false){
                $Num_Projet = str_replace("AA", "EE", $Num_Projet);
            }
        }

        if(in_array($Num_Projet, $tabsTechnique)){
            $req= $conn->query("SELECT * FROM GRH_INTERVENANT WHERE NO_PROJ = '$Num_Projet' AND PHASE = '$phase' AND PERS_ID = '$pers' AND ORDRE = '$ordre' ");
            $donne = $req->fetch();
            if ($donne==false){
                $Num_Projet = str_replace("EE", "AA", $Num_Projet);
            }
        }

        $req= $conn->query("SELECT * FROM GRH_INTERVENANT WHERE NO_PROJ = '$Num_Projet_origine' AND PHASE = '$phase' AND PERS_ID = '$pers' AND ORDRE = '$ordre' ");
        $donne = $req->fetch();
        if ($donne!=false){
            $Num_Projet = $Num_Projet_origine;
        }

    }
    catch(Exception $e){
        ?>
        <p>Connexion interrompue. La connexion au serveur ne semble pas stable. <a href="dashboard.php">Veuillez recommencer !</a></p>

    <?php
        die();
    }







