<?php

    /*
     * ON RECUPERE LES HEURES DANS LA TABLE TEMPORAIRE RELEVE HEURE
     */
    $req = $conn->query("SELECT * FROM GRH_RELEVE_HEURE_TEMP ORDER BY WEEKNO");

    /*
     * DEFINITION DES CERTAINES VARIABLES POUR LES UTILISES DANS LA BOUCLES
     */
    $autorise=true;
    $weekstatut = true;
    $weekcompare = null;

    while($donnee = $req->fetch() OR $autorise==true){

        /*
         * S'IL Y A DES DONNES DANS LA TABLE TEMPORAIRE RELEVE HEURE ALORS
         */
        if(!empty($donnee)){


            /*
             * INITIALISATION DES PREMIERES HEURES REG ET SUPP
             */
            if($weekstatut == true){
                $weekcompare = $donnee['WEEKNO'];
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

                $weekstatut = false;
            }


            /*
             * WEEKSATUT PASSE A FALSE DANS LE RESTE DE LA BOUCLE DONC C'EST DES INCREMENTATION
             * JUSQU'A TROUVE UN NUMERO DE WEEKEND DIFFERENT
             */
            else if($weekcompare == $donnee['WEEKNO']){

                if((float)($donnee['HREG'])>8){
                    $heures_reg += 8;
                }
                else{
                    $heures_reg += (float)($donnee['HREG']);
                }

                if((float)$donnee['HSUPP']- 8 > 0){
                    $heures_sup += (float)($donnee['HSUPP'])-8;
                }
                else{
                    $heures_sup += 0;
                }
            }


            /*
             * S'IL TROUVE UN NOUVEAU NUMERO DE WEEK END ALORS IL AJOUTES LES HEURES COMPATIBILISES POUR LA SEMAINES
             * ET INSERT DANS LA BASE DE DONNES
             */
            if($weekcompare != $donnee['WEEKNO'] and $weekcompare != null){


                /*
                 * RECUPERE LA DATE DU DIMANCHE CORRESPONDANT AU DERNIER JOUR(VENDREDI)
                 */
                $dimanche = new DateTime();
                $dimanche->setISOdate($donnee['ANNEE'], $weekcompare, 7);
                $weekdate = $dimanche->format('d/m/y');

                $heure_tot = $heures_reg+$heures_sup;

                $pers = $donnee['PERS_ID'];
                $year = $donnee['ANNEE'];

                $conn->exec("INSERT INTO GRH_RELEVE_GT_TEMP(ANNEE, WEEKNO, PERS_ID, HREG, HSUPP, WEEKDATE, HTOTAL, STATUT) VALUES ('$year', '$weekcompare', '$pers', '$heures_reg', '$heures_sup', TO_DATE('$weekdate','dd/mm/rr'), '$heure_tot', 'N')");
                $weekcompare = $donnee['WEEKNO'];

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

            $conn->exec("INSERT INTO GRH_RELEVE_GT_TEMP(ANNEE, WEEKNO, PERS_ID, HREG, HSUPP, WEEKDATE, HTOTAL, STATUT) VALUES ('$year', '$weekcompare', '$pers', '$heures_reg', '$heures_sup', TO_DATE('$weekdate','dd/mm/rr'), '$heure_tot', 'N')");
            $autorise = false;
        }

    }