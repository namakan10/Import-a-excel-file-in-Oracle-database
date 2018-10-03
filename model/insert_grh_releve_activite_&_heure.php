<?php

    $number = 1;
    while(($fileop = fgetcsv($handle, '2000', ",")) !== false){

        /*
         * EXTRACTION DE LA LIGNE EN COURS
         */
        include 'extraction_data.php';

        //if($aut == true){


            /*
             *  VERIFICATION SI CETTE PERSONNE EST AFFECTE A CE PROJET ET LA PHASE CONDITION SUR DONNE
             */

            $req= $conn->query("SELECT * FROM GRH_INTERVENANT WHERE NO_PROJ = '$Num_Projet' AND PHASE = '$phase' AND PERS_ID = '$pers' AND ORDRE = '$ordre' ");
            $donne = $req->fetch();
            if($donne == false AND $Num_Projet != null){
                $_SESSION['update'] = $_POST['prenom'] ." n'est pas affecté aux projet " .$Num_Projet_origine. " date du champ : " .$dt;
            }

            //SI OUI
            else if($donne!=false OR $Num_Projet == null){

                /*
                 * RECUPERATION DE LA DATE MAXIMALE POUR LA COMPARE AUX AUTRES DATES
                 */
                $req = $conn->query("SELECT MAX(DATE_JOUR) FROM GRH_RELEVE_ACTIVITE WHERE PERS_ID = '$pers' ");
                $donne = $req->fetch();
                $datemax0 = $donne[0];


                /*
                 * VERIFCATION SI LA DATE SELECTIONNER EST SUPERIEURE A LA ACTUELLE OU SI C'EST LA MEME DATE AVEC NUMERO DE PROJET DIFFERENT
                 */
                $datemax  = DateTime::createFromFormat('d/m/y', $datemax0);
                $date  = DateTime::createFromFormat('d/m/y', $dt);


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
                 * RECUPERATION DU NUMERO CONCERNANT LE PROJET DE GRH_RELEVE_ACTIVITE
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

                /*
                 * RECUPERATION DU NUMERO CONCERNANT LE PROJET DE GRH_RELEVE_HEURE MEME PROCEDE
                 */
                $req1 = $conn->query("SELECT MAX(NO) FROM GRH_RELEVE_HEURE WHERE PERS_ID = '$pers' AND NO_PROJ = '$Num_Projet' AND PHASE = '$phase' AND ORDRE = 1 AND ANNEE = '$year' AND WEEKNO = '$week' ");
                $donne1 = $req1->fetch();
                $nomax1 = $donne1[0];
                $req2 = $conn->query("SELECT MAX(NO) FROM GRH_RELEVE_HEURE_TEMP WHERE PERS_ID = '$pers' AND NO_PROJ = '$Num_Projet' AND PHASE = '$phase' AND ORDRE = 1 AND ANNEE = '$year' AND WEEKNO = '$week' ");
                $donne2 = $req2->fetch();
                $nomax2 = $donne2[0];


                if($nomax1==null AND $nomax2==null){
                    $num2 = 1;
                }
                else if($nomax2==null AND $nomax1 != null){
                    $num2 = $nomax1+1;
                }
                else if($nomax2!=null AND $nomax1 != null){
                    $num2=$nomax2+1;
                }

                else if($nomax2!=null AND $nomax1==null){
                    $num2=$nomax2+1;
                }


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


                if($datemax==null ){

                    if($Num_Projet == null){
                        $Num_Projet = " ";
                    }


                    /*
                     * INSERTION DANS RELEVEE ACTIVITE TEMP
                     */
                    $req = $conn->exec("INSERT INTO GRH_RELEVE_ACTIVITE_TEMP(NO, DATE_JOUR, NO_PROJ, PHASE, AFF_NO, PERS_ID, DESCRIPTION, ANNEE, WEEKNO, REL_PERS_ID, COL_ID)
                                  VALUES('$num', TO_DATE('$dt', 'dd/mm/rrrr'), '$Num_Projet', '$phase', '$aff', '$pers', '$Compte_Rendu', '$year', '$week', '$pers', '$number' )");


                    /*
                     * INSERTION DANS RELEVE HEURE TEMP
                     */
                    $heures_reg = str_replace('.', ',', $heures_reg);
                    $heures_sup = str_replace('.', ',', $heures_sup);
                    $heure_tot = str_replace('.', ',', $heure_tot);
                    $conn->exec("INSERT INTO GRH_RELEVE_HEURE_TEMP(NO, HREG, HTOTAL, HSUPP, DATE_JOUR, ANNEE, WEEKNO, PERS_ID, PHASE, NO_PROJ, ORDRE, REL_PERS_ID, FLAG_IMP, COL_ID) 
                                  VALUES ('$num2', '$heures_reg', '$heure_tot', '$heures_sup', TO_DATE('$dt', 'dd/mm/rrrr'), '$year', '$week', '$pers', '$phase', '$Num_Projet', '1', '$pers', 'N', '$number')");



                    $_SESSION['effectue'] = 'succes';
                    $number++;

                }
                else if($datemax->getTimestamp() <= $date->getTimestamp()){

                    if($Num_Projet == null){
                        $Num_Projet = " ";
                    }

                    $autorisation = true;

                    if($datemax->getTimestamp() == $date->getTimestamp()){

                        /*
                         * RECUPERATION DES PROJETS CONCERNANT CETTE DATE ET ON LE COMPARE AVEC CE QU'ON VEUT INSERSER
                         */
                        $req = $conn->query("SELECT NO_PROJ, DESCRIPTION FROM GRH_RELEVE_ACTIVITE WHERE PERS_ID = '$pers' AND DATE_JOUR = '$datemax0' ");

                        while($donne = $req->fetch()){
                            $description = str_replace("'", "''", $donne['DESCRIPTION']);
                            if($donne['NO_PROJ'] == $Num_Projet AND $Compte_Rendu == $description){
                                $autorisation = false;
                            }
                        }
                    }

                    if($autorisation == true){

                        /*
                         * INSERTION DANS RELEVE ACTIVITE TEMP
                         */
                        $req = $conn->exec("INSERT INTO GRH_RELEVE_ACTIVITE_TEMP(NO, DATE_JOUR, NO_PROJ, PHASE, AFF_NO, PERS_ID, DESCRIPTION, ANNEE, WEEKNO, REL_PERS_ID, COL_ID)
                                  VALUES('$num', TO_DATE('$dt', 'dd/mm/rrrr'), '$Num_Projet', '$phase', '$aff', '$pers', '$Compte_Rendu', '$year', '$week', '$pers', '$number' )");

                        /*
                         * INSERTION DANS RELEVE HEURE TEMP
                         */
                        $heures_reg = str_replace('.', ',', $heures_reg);
                        $heures_sup = str_replace('.', ',', $heures_sup);
                        $heure_tot = str_replace('.', ',', $heure_tot);
                        $conn->exec("INSERT INTO GRH_RELEVE_HEURE_TEMP(NO, HREG, HTOTAL, HSUPP, DATE_JOUR, ANNEE, WEEKNO, PERS_ID, PHASE, NO_PROJ, ORDRE, REL_PERS_ID, FLAG_IMP, COL_ID) 
                                  VALUES ('$num2', '$heures_reg', '$heure_tot', '$heures_sup', TO_DATE('$dt', 'dd/mm/rrrr'), '$year', '$week', '$pers', '$phase', '$Num_Projet', '1', '$pers', 'N', '$number')");



                        $_SESSION['upload'] = true;
                        $number++;
                    }

                }
            }
        //}

    }