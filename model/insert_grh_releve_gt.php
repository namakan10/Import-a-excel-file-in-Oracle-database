<?php

    /*
     * ON RECUPERE LES HEURES DANS LA TABLE TEMPORAIRE RELEVE HEURE
     */
    $req = $conn->query("SELECT * FROM GRH_RELEVE_HEURE_TEMP ORDER BY WEEKNO");

    /*
     * DEFINITION DE CERTAINES VARIABLES POUR LES UTILISES DANS LA BOUCLES
     */
    $autorise=true;
    $weekstatut = true;
    $weekcompare = null;
    $update = false;
    $checklastweek = true;


    while($donnee = $req->fetch() OR $autorise==true){

        /*
         * S'IL Y A DES DONNES DANS LA TABLE TEMPORAIRE RELEVE HEURE ALORS
         */
        if(!empty($donnee)){

            $pers = $donnee['PERS_ID'];
            $year = $donnee["ANNEE"];

            $lastweekno = null;
            if($checklastweek == true){
                $req = $conn->query("SELECT MAX(NO) FROM GRH_RELEVE_GT WHERE ANNEE='$year' AND PERS_ID = '$pers'");
                $result = $req->fetch();
                $lastweekno = $result[0];
                $checklastweek = false;
            }

            $donnee['HREG'] = str_replace(',', '.', $donnee['HREG']);
            $donnee['HSUPP'] = str_replace(',', '.', $donnee['HSUPP']);

            /*
             * INITIALISATION DES PREMIERES HEURES REG ET SUPP
             */
            if($weekstatut == true){
                $weekcompare = $donnee['WEEKNO'];
                if($lastweekno == $weekcompare){
                    $req = $conn->query("SELECT * FROM GRH_RELEVE_GT WHERE WEEKNO='$lastweekno' AND PERS_ID = '$pers' AND ANNEE='$year'");
                    while ($result = $req->fetch()){
                        $result['HREG'] = str_replace(',', '.', $result['HREG']);
                        $result['HSUPP'] = str_replace(',', '.', $result['HSUPP']);
                        $heures_reg = (float)($result['HREG']);
                        $heures_sup = (float)($result['HSUPP']);
                        $heure_tot = (float)($result['HTOTAL']);
                        $update = true;
                    }
                }
                else{
                    if((float)($donnee['HREG']) > 8){
                        $heures_reg = 8;
                    }
                    else{
                        if($donnee['HREG'] = ".5"){
                            $heures_reg = 0.5;
                        }
                        else{
                            $heures_reg = (float)($donnee['HREG']);
                        }

                    }

                    if((float)($donnee['HSUPP']) - 8 > 0){
                        $heures_sup = (float)($donnee['HSUPP']) - 8;
                    }
                    else{
                        $heures_sup = 0;
                    }
                }

                $weekstatut = false;
            }


            /*
             * WEEKSATUT PASSE A FALSE DANS LE RESTE DE LA BOUCLE DONC C'EST DES INCREMENTATION
             * JUSQU'A TROUVE UN NUMERO DE WEEKEND DIFFERENT
             */
            else if($weekcompare == $donnee['WEEKNO']){
                $heures_reg += (float)($donnee['HREG']);
                $heures_sup += (float)($donnee['HSUPP']);

                $pers = $donnee['PERS_ID'];
                $year = $donnee['ANNEE'];

            }


            /*
             * S'IL TROUVE UN NOUVEAU NUMERO DE WEEK END ALORS IL AJOUTES LES HEURES COMPATIBILISES POUR LA SEMAINE
             * ET INSERT DANS LA BASE DE DONNES
             */
            if($weekcompare != $donnee['WEEKNO'] and $weekcompare != null){


                if($update == true){

                    $heure_tot = $heure_tot + $heures_sup+ $heure_tot;
                    $heures_reg = str_replace('.', ',', $heures_reg);
                    $heures_sup = str_replace('.', ',', $heures_sup);
                    $heure_tot = str_replace('.', ',', $heure_tot);

                    $query = $conn ->prepare('UPDATE GRH_RELEVE_GT SET HSUPP = :hsupp, HREG = :hreg, HTOTAL = :htotal  WHERE WEEKNO = :weekno AND PERS_ID = :pers AND ANNEE = :annee');
                    $query->execute(array(

                        'hsupp' => $heures_sup,
                        'hreg' => $heures_reg,
                        'htotal' => $heure_tot,
                        'weekno' => $weekcompare,
                        'pers' => $pers,
                        'annee' => $year,
                    ));

                    $update = false;
                }
                else{
                    /*
                     * RECUPERE LA DATE DU DIMANCHE CORRESPONDANT AU DERNIER JOUR(VENDREDI)
                     */
                    $dimanche = new DateTime();
                    $dimanche->setISOdate($donnee['ANNEE'], $weekcompare, 7);
                    $weekdate = $dimanche->format('d/m/y');

                    $heure_tot = $heures_reg+$heures_sup;





                    $heures_reg = str_replace('.', ',', $heures_reg);
                    $heures_sup = str_replace('.', ',', $heures_sup);
                    $heure_tot = str_replace('.', ',', $heure_tot);
                    $conn->exec("INSERT INTO GRH_RELEVE_GT(ANNEE, WEEKNO, PERS_ID, HREG, HSUPP, WEEKDATE, HTOTAL, STATUT) VALUES ('$year', '$weekcompare', '$pers', '$heures_reg', '$heures_sup', TO_DATE('$weekdate','dd/mm/rr'), '$heure_tot', 'N')");
                    $weekcompare = $donnee['WEEKNO'];
                }


                /*
                 * NOUVEAU POINTAGE SUR LES HEURES REG ET HSUPP
                 */
                if((float)($donnee['HREG']) > 8){
                    $heures_reg = 8;
                }
                else{
                    $heures_reg = (float)($donnee['HREG']);
                }

                if((float)($donnee['HSUPP']) - 8 > 0){
                    $heures_sup = (float)($donnee['HSUPP']) - 8;
                }
                else{
                    $heures_sup = 0;
                }

            }

        }

        /*
         * QUAND LES DONNES SERONT FINIS ILS DOIVENT ETRE INSEREES UNE DERNIERE FOIS D'OU LE ELSE
         */
        else{

            $dimanche = new DateTime();
            $dimanche->setISOdate($year, $weekcompare, 7);
            $weekdate = $dimanche->format('d/m/y');

            $heure_tot = $heures_reg+$heures_sup;


            $heures_reg = str_replace('.', ',', $heures_reg);
            $heures_sup = str_replace('.', ',', $heures_sup);
            $heure_tot = str_replace('.', ',', $heure_tot);

            $conn->exec("INSERT INTO GRH_RELEVE_GT(ANNEE, WEEKNO, PERS_ID, HREG, HSUPP, WEEKDATE, HTOTAL, STATUT) VALUES ('$year', '$weekcompare', '$pers', '$heures_reg', '$heures_sup', TO_DATE('$weekdate','dd/mm/rr'), '$heure_tot', 'N')");
            $autorise = false;
        }

    }