<?php

    $req = $conn->query("SELECT * FROM GRH_RELEVE_ACTIVITE_TEMP");
    $check = $req->fetch();
    if($check == null){

    }
    else{
        $number = 1;
        while(($fileop = fgetcsv($handle, '2000', ",")) !== false){

            /*
             * EXTRACTION DES DONNEES DE LA LIGNE EN COURS
             */
            include 'extraction_data.php';

            /*
             * VERIFICATION SI LA PERSONNE EST AFFECTEE AU PROJET ET LA PHASE CONDITION SUR DONNE
             */
            $req= $conn->query("SELECT * FROM GRH_INTERVENANT WHERE NO_PROJ = '$Num_Projet' AND PHASE = '$phase' AND PERS_ID = '$pers' AND ORDRE = '$ordre' ");
            $donne = $req->fetch();

            if($donne!=false){

            /*
             * RECUPERATION DES HEURES
             */
            if((float)($Nb_Heures)>8){
                $heures_reg = 8;
            }
            else if((float)($Nb_Heures) == null){
                $heures_reg = 0;
            }
            else{
                $heures_reg = (float)($Nb_Heures);
            }

            if((float)($Nb_Heures)-8 > 0){
                $heures_sup = (float)($Nb_Heures)-8;
            }
            else{
                $heures_sup = 0;
            }
            $heure_tot = $heures_sup+$heures_reg;


            /*
             * RECUPERATION DE LA DATE MAXIMALE POUR LA COMPARE AUX AUTRES DATES
             */
            $req = $conn->query("SELECT MAX(DATE_JOUR) FROM GRH_RELEVE_HEURE WHERE PERS_ID = '$pers' ");
            $donne = $req->fetch();
            $datemax = $donne[0];


            /*
             * RECUPERATION DU NUMERO CONCERNANT LE PROJET
             * ON CHERCHE S'IL Y A UN NUMERO MAX CORRESPONDANT AUX CRITRES D'UNICITE (NO, NO_PROJ, PERS_ID, PHASE, ORDRE, ANNEE, WEEKNO)
             */
            $req1 = $conn->query("SELECT MAX(NO) FROM GRH_RELEVE_HEURE WHERE PERS_ID = '$pers' AND NO_PROJ = '$Num_Projet' AND PHASE = '$phase' AND ORDRE = 1 AND ANNEE = '$year' AND WEEKNO = '$week' ");
            $donne1 = $req1->fetch();
            $nomax1 = $donne1[0];
            $req2 = $conn->query("SELECT MAX(NO) FROM GRH_RELEVE_HEURE_TEMP WHERE PERS_ID = '$pers' AND NO_PROJ = '$Num_Projet' AND PHASE = '$phase' AND ORDRE = 1 AND ANNEE = '$year' AND WEEKNO = '$week' ");
            $donne2 = $req2->fetch();
            $nomax2 = $donne2[0];

            /*
             * SI LE NUMERO MAX EXISTE DANS L'UNE DES DEUX TABLES ON L'INCREMENTE UN
             * SINON ON LE LUI ATTRIBUT UN
             */
            if($nomax1==null AND $nomax2==null){
                $num = 1;
            }
            else if($nomax2==null AND $nomax1 != null){
                $num = $nomax1+1;
            }
            else if($nomax2!=null AND $nomax1 != null){
                $num=$nomax2+1;
            }

            else if($nomax2!=null AND $nomax1==null){
                $num=$nomax2+1;
            }


            $req = $conn->query("SELECT NO_PROJ FROM GRH_RELEVE_HEURE WHERE PERS_ID = '$pers' AND DATE_JOUR = '$datemax' ");
            $autorisation = true;
            while($donne = $req->fetch()){
                if($donne['NO_PROJ'] == $Num_Projet){
                    $autorisation = true;
                }
            }

            /*
             * VERIFCATION SI LA DATE SELECTIONNER EST SUPERIEURE A LA ACTUELLE OU SI C'EST LA MEME DATE AVEC NUMERO DE PROJET DIFFERENT
             */
            $datemax0  = DateTime::createFromFormat('d/m/y', $datemax);
            $date  = DateTime::createFromFormat('d/m/y', $dt);

            if ( $datemax==null){
                $heures_reg = str_replace('.', ',', $heures_reg);
                $heures_sup = str_replace('.', ',', $heures_sup);
                $heure_tot = str_replace('.', ',', $heure_tot);
                $conn->exec("INSERT INTO GRH_RELEVE_HEURE_TEMP(NO, HREG, HTOTAL, HSUPP, DATE_JOUR, ANNEE, WEEKNO, PERS_ID, PHASE, NO_PROJ, ORDRE, REL_PERS_ID, FLAG_IMP, COL_ID) 
                                  VALUES ('$num', '$heures_reg', '$heure_tot', '$heures_sup', TO_DATE('$dt', 'dd/mm/rrrr'), '$year', '$week', '$pers', '$phase', '$Num_Projet', '1', '$pers', 'O', '$number')");
                $_SESSION['effectue'] = 'succes';
                $number++;
            }
            else if($datemax0->getTimestamp() <= $date->getTimestamp() AND $autorisation == true){
                $heures_reg = str_replace('.', ',', $heures_reg);
                $heures_sup = str_replace('.', ',', $heures_sup);
                $heure_tot = str_replace('.', ',', $heure_tot);
                $conn->exec("INSERT INTO GRH_RELEVE_HEURE_TEMP(NO, HREG, HTOTAL, HSUPP, DATE_JOUR, ANNEE, WEEKNO, PERS_ID, PHASE, NO_PROJ, ORDRE, REL_PERS_ID, FLAG_IMP, COL_ID) 
                                    VALUES ('$num', '$heures_reg', '$heure_tot', '$heures_sup', TO_DATE('$dt', 'dd/mm/rrrr'), '$year', '$week', '$pers', '$phase', '$Num_Projet', '1', '$pers', 'O', '$number')");
                $_SESSION['effectue'] = 'succes';
                $number++;
            }


            }
            /*
            * SI ELLE N'EST PAS AFFECTEE A UN PROJET
            */
            else{
              //  if($Jour!='Samedi' AND $Jour !='Dimanche'){

                    /*
                    $_SESSION['erreuraffec'] = $_POST['prenom'] ." n'est pas affecté à un ou plusieurs projets";
                    */
               //}
            }

        }
    }
