<?php

    //TRANSFERT DES DONNEES DE LA TABLE TEMPORAIRE VERS LA TABLE FINAL

    /*
     * GRH_RELEVE_HEURE
     */
    $req = $conn->query("SELECT * FROM GRH_RELEVE_HEURE_TEMP");
    while ($donne = $req->fetch()){
        $num = $donne['NO'];
        $heures_reg = $donne['HREG'];
        $heures_sup = $donne['HSUPP'];
        $heure_tot =  $donne['HTOTAL'];
        $dt =  $donne['DATE_JOUR'];
        $year = $donne['ANNEE'];
        $week = $donne['WEEKNO'];
        $phase = $donne['PHASE'];
        $Num_Projet = $donne['NO_PROJ'];
        $pers = $donne['PERS_ID'];

        $conn->exec("INSERT INTO GRH_RELEVE_HEURE_TEMP1(NO, HREG, HTOTAL, HSUPP, DATE_JOUR, ANNEE, WEEKNO, PERS_ID, PHASE, NO_PROJ, ORDRE, REL_PERS_ID, FLAG_IMP) 
                                    VALUES ('$num', '$heures_reg', '$heure_tot', '$heures_sup', TO_DATE('$dt', 'dd/mm/rrrr'), '$year', '$week', '$pers', '$phase', '$Num_Projet', '1', '$pers', 'O')");
    }
    $conn->exec("DELETE FROM GRH_RELEVE_HEURE_TEMP");


    /*
     * GRH_RELEVE_ACTIVITE
     */
    $req = $conn->query("SELECT * FROM GRH_RELEVE_ACTIVITE_TEMP");
    while ($donne = $req->fetch()){
        $num = $donne['NO'];
        $Compte_Rendu = $donne['DESCRIPTION'];
        $dt =  $donne['DATE_JOUR'];
        $aff = $donne['AFF_NO'];
        $year = $donne['ANNEE'];
        $week = $donne['WEEKNO'];
        $phase = $donne['PHASE'];
        $Num_Projet = $donne['NO_PROJ'];
        $pers = $donne['PERS_ID'];
        $var1 = array("é", "è", "ê", "ë", "à","î", "ï", "ç", "'", "’", "ô", "°", "É", "–", "«", "»", "û", "…", "œ");
        $var2 = array("e", "e", "e", "e","a","i", "i", "c", "''", "''", "o", "umero ", "E", "-", "(", ")", "u", " ", "oe");

        if ($Compte_Rendu == null){
            $Compte_Rendu = " ";
        }

        $Compte_Rendu = str_replace($var1, $var2, $Compte_Rendu);
        $conn->exec("INSERT INTO GRH_RELEVE_ACTIVITE_TEMP1(NO, DATE_JOUR, NO_PROJ, PHASE, AFF_NO, PERS_ID, DESCRIPTION, ANNEE, WEEKNO, REL_PERS_ID)
                                  VALUES('$num','$dt', '$Num_Projet', '$phase', '$aff', '$pers', '$Compte_Rendu', '$year', '$week', '$pers')");
    }

    $conn->exec("DELETE FROM GRH_RELEVE_ACTIVITE_TEMP");

   $_SESSION['effectue'] = 'non';

   $_SESSION['update'] = 'Les données ont été importées avec succes';