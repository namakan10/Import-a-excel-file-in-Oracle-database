<?php

    $number = 1;
    while(($fileop = fgetcsv($handle, '2000', ",")) !== false AND $autorisation == true){

        /*
         * EXTRACTION DE LA LIGNE EN COURS
         */
        include 'extraction_data.php';


        /*
         *  VERIFICATION SI CETTE PERSONNE EST AFFECTE A CE PROJET ET LA PHASE CONDITION SUR DONNE
        */
        $req= $conn->query("SELECT * FROM GRH_INTERVENANT WHERE NO_PROJ = '$Num_Projet' AND PHASE = '$phase' AND PERS_ID = '$pers' AND ORDRE = '$ordre' ");
        $donne = $req->fetch();
        if($donne == false AND $Num_Projet != null){
            $_SESSION['update'] = $_POST['prenom'] ." n'est pas affecté aux projet " .$Num_Projet_origine. " date du champ : " .$dt;
           // $conn->exec("DELETE FROM GRH_RELEVE_ACTIVITE_TEMP");
            //$_SESSION['effectue'] = 'non';
            //$autorisation = false;
        }


        //SI OUI
        if($donne!=false){
            /*
             * RECUPERATION DE LA DATE MAXIMALE POUR LA COMPARE AUX AUTRES DATES
             */
            $req = $conn->query("SELECT MAX(DATE_JOUR) FROM GRH_RELEVE_ACTIVITE WHERE PERS_ID = '$pers' ");
            $donne = $req->fetch();
            $datemax = $donne[0];


            /*
             * REMPLACEMENT DES CARACTERES SPECIAUX DANS LE COMPTE RENDU
             */
            $var1 = array("é", "è", "ê", "ë", "à","î", "ï", "ç", "'", "’", "ô", "°", "É", "–", "«", "»", "û", "…", "œ");
            $var2 = array("e", "e", "e", "e","a","i", "i", "c", "''", "''", "o", "umero ", "E", "-", "(", ")", "u", " ", "oe");

            if ($Compte_Rendu == null){
                $Compte_Rendu = " ";
            }

            $Compte_Rendu = str_replace($var1, $var2, $Compte_Rendu);


            /*
             * RECUPERATION DES PROJETS CONCERNANT CETTE DATE ET ON LE COMPARE AVEC CE QU'ON VEUT INSERSER
             */
            $req = $conn->query("SELECT NO_PROJ, DESCRIPTION FROM GRH_RELEVE_ACTIVITE WHERE PERS_ID = '$pers' AND DATE_JOUR = '$datemax' ");
            $autorisation = true;
            while($donne = $req->fetch()){

                $description = str_replace("'", "''", $donne['DESCRIPTION']);
                if($donne['NO_PROJ'] == $Num_Projet AND $Compte_Rendu == $description){
                    $autorisation = false;
                }
            }

            /*
             * RECUPERATION DU NUMERO CONCERNANT LE PROJET
             * ON CHERCHE S'IL Y A UN NUMERO MAX CORRESPONDANT AUX CRITRES D'UNICITE (NO, NO_PROJ, PERS_ID, PHASE, AFF_NO)
             */
            $req = $conn->query("SELECT MAX(NO) FROM GRH_RELEVE_ACTIVITE WHERE PERS_ID = '$pers' AND NO_PROJ = '$Num_Projet' AND PHASE = '$phase' AND AFF_NO = 1 ");
            $donne = $req->fetch();
            $nomax1 = $donne[0];
            $req = $conn->query("SELECT MAX(NO) FROM GRH_RELEVE_ACTIVITE_TEMP WHERE PERS_ID = '$pers' AND NO_PROJ = '$Num_Projet' AND PHASE = '$phase' AND AFF_NO = 1 ");
            $donne = $req->fetch();
            $nomax2 = $donne[0];

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


            //var_dump($dt);
            //var_dump($Num_Projet);

            /*
             * VERIFCATION SI LA DATE SELECTIONNER EST SUPERIEURE A LA ACTUELLE OU SI C'EST LA MEME DATE AVEC NUMERO DE PROJET DIFFERENT
             */
            $datemax0  = DateTime::createFromFormat('d/m/y', $datemax);
            $date  = DateTime::createFromFormat('d/m/y', $dt);



            if($datemax==null  AND $Num_Projet != null){
                /*
                var_dump(2);
                var_dump($datemax0);
                var_dump($date);
                var_dump($datemax);
                var_dump($dt);
                die();*/
                $req = $conn->exec("INSERT INTO GRH_RELEVE_ACTIVITE_TEMP(NO, DATE_JOUR, NO_PROJ, PHASE, AFF_NO, PERS_ID, DESCRIPTION, ANNEE, WEEKNO, REL_PERS_ID, COL_ID)
                                  VALUES('$num', TO_DATE('$dt', 'dd/mm/rrrr'), '$Num_Projet', '$phase', '$aff', '$pers', '$Compte_Rendu', '$year', '$week', '$pers', '$number' )");
                $_SESSION['effectue'] = 'succes';
                $number++;

            }
            else if($datemax0->getTimestamp() <= $date->getTimestamp() AND $autorisation == true AND $Num_Projet != null){

                /*
                var_dump(1);
                var_dump($datemax0);
                var_dump($date);
                var_dump($datemax);
                var_dump($dt);
                die();*/

                $req = $conn->exec("INSERT INTO GRH_RELEVE_ACTIVITE_TEMP(NO, DATE_JOUR, NO_PROJ, PHASE, AFF_NO, PERS_ID, DESCRIPTION, ANNEE, WEEKNO, REL_PERS_ID, COL_ID)
                                  VALUES('$num', TO_DATE('$dt', 'dd/mm/rrrr'), '$Num_Projet', '$phase', '$aff', '$pers', '$Compte_Rendu', '$year', '$week', '$pers', '$number' )");
                $_SESSION['effectue'] = 'succes';
                $number++;
            }


        }

        /*
         * SI elle n'est pas affecté au projet
         */
       else{
            if($Jour!='Samedi' AND $Jour !='Dimanche'){

                //$_SESSION['erreuraffec'] = $_POST['prenom'] ." n'est pas affecté aux projet " .$Num_Projet;
                //$conn->exec("DELETE FROM GRH_RELEVE_ACTIVITE_TEMP");
                //echo "<script type='text/javascript'>document.location.replace('import.php');</script>";

            }
        }
    }