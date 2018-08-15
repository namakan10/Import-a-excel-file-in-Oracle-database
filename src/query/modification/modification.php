<?php
    if(isset($_POST['modifier'])){


        /*
         * RECUPERATION DE L'ID
         */

        $id = $_POST['id'];
        //RECUPERATION DE LA MODIFICATION A FAIRE
        $modif = $_POST['modif'];

        //MODIFICATION DE LA DATE
        if ($_POST['select']=='Date'){

            $modif = $_POST['modif'];
            $query = $conn->query("SELECT to_date('$modif','dd/mm/rrrr') FROM DUAL");
            $modifier = $query->fetch();
            $modif = $modifier[0];

            //MISE A JOUR DU NUMERO WEEK-END
            $query = $conn->query("SELECT to_char( (to_date('$modif','dd/mm/rrrr')), 'WW' ) FROM dual");
            $week0= $query->fetch();
            $week = $week0[0];

            $query = $conn ->prepare('UPDATE GRH_RELEVE_HEURE_TEMP SET DATE_JOUR = :modif, WEEKNO = :weekno WHERE COL_ID = :id');
            $query->execute(array(

                'modif' => $modif,
                'weekno' => $week,
                'id' => $id,
            ));

            $query = $conn ->prepare('UPDATE GRH_RELEVE_ACTIVITE_TEMP SET DATE_JOUR = :modif, WEEKNO = :weekno WHERE COL_ID = :id');
            $query->execute(array(

                'modif' => $modif,
                'weekno' => $week,
                'id' => $id,
            ));

            $_SESSION['update'] = "Modification effectué avec success !";
        }

        //MODIFICATION DU NUMERO PROJET
        else if ($_POST['select']=='Num_PROJ'){
            $pers = $_SESSION['PERS_ID'];
            $_SESSION['modif'] = $_POST['modif'];


            $req= $conn->query("SELECT * FROM GRH_INTERVENANT WHERE NO_PROJ = '$modif' AND PERS_ID = '$pers' AND ORDRE = 1 ");
            $donne = $req->fetch();
            if($donne!=false){
                $query = $conn ->prepare('UPDATE GRH_RELEVE_HEURE_TEMP SET NO_PROJ = :modif WHERE COL_ID = :id');
                $query->execute(array(

                    'modif' => $_POST['modif'],
                    'id' => $id,
                ));

                $query = $conn ->prepare('UPDATE GRH_RELEVE_ACTIVITE_TEMP SET NO_PROJ = :modif WHERE COL_ID = :id');
                $query->execute(array(

                    'modif' => $_POST['modif'],
                    'id' => $id,
                ));
                $_SESSION['update'] = "Modification effectué avec success !";
            }
            else{
                $_SESSION ['erreur'] = $_SESSION['PERS_PRENOM']. " n'est pas affecté au projet ".$_SESSION['modif']." ou le projet n'existe pas";
            }



        }

        //MODIFICATION DE LA PHASE
        else if ($_POST['select']=='Phase'){
            $query = $conn ->prepare('UPDATE GRH_RELEVE_HEURE_TEMP SET PHASE = :modif WHERE COL_ID = :id');
            $query->execute(array(

                'modif' => $_POST['modif'],
                'id' => $id,
            ));

            $query = $conn ->prepare('UPDATE GRH_RELEVE_ACTIVITE_TEMP SET PHASE = :modif WHERE COL_ID = :id');
            $query->execute(array(

                'modif' => $_POST['modif'],
                'id' => $id,
            ));

            $_SESSION['update'] = "Modification effectué avec success !";

        }

        //MODIFICATION DE LA DESCRIPTION
        else if ($_POST['select']=='Description'){
            $query = $conn ->prepare('UPDATE GRH_RELEVE_ACTIVITE_TEMP SET DESCRIPTION = :modif WHERE COL_ID = :id');
            $query->execute(array(

                'modif' => $_POST['modif'],
                'id' => $id,
            ));
            $_SESSION['update'] = "Modification effectué avec success !";
        }

        //MODIFICATION DES HEURES REGULIERES
        else if ($_POST['select']=='Heures regulières'){


            if($_POST['modif']<=8){

                $query = $conn->query("SELECT HSUPP FROM GRH_RELEVE_HEURE_TEMP WHERE COL_ID = :id");

                $hsupp0= $query->fetch();

                $hsupp = intval($hsupp0[0]);

                $htot = $hsupp + $_POST['modif'];


                $query = $conn ->prepare('UPDATE GRH_RELEVE_HEURE_TEMP SET HREG = :modif WHERE COL_ID = :id');
                $query->execute(array(

                    'modif' => $_POST['modif'],
                    'id' => $id,
                ));



                $query = $conn ->prepare('UPDATE GRH_RELEVE_HEURE_TEMP SET HTOTAL = :modif WHERE COL_ID = :id');
                $query->execute(array(

                    'modif' => $htot,
                    'id' => $id,
                ));
                $_SESSION['update'] = "Modification effectué avec success !";
            }

            else{
                $_SESSION ['erreur'] = "L'heure regulière ne depasse pas 8 heures";
            }
        }

        //MODIFICATION DE L'HEURE SUPPLEMENTAIRES
        else if ($_POST['select']=='Heures supplementaires'){
            $query = $conn->query("SELECT HREG FROM GRH_RELEVE_HEURE_TEMP WHERE COL_ID = :id");
            $hreg0= $query->fetch();
            $hreg = intval($hreg0[0]);

            $htot = $hreg + $_POST['modif'];


            $query = $conn ->prepare('UPDATE GRH_RELEVE_HEURE_TEMP SET HSUPP = :modif WHERE COL_ID = :id');
            $query->execute(array(

                'modif' => $_POST['modif'],
                'id' => $id,
            ));

            $query = $conn ->prepare('UPDATE GRH_RELEVE_HEURE_TEMP SET HTOTAL = :modif WHERE COL_ID = :id');
            $query->execute(array(

                'modif' => $htot,
                'id' => $id,
            ));
            $_SESSION['update'] = "Modification effectué avec success !";
        }

        $_SESSION['validate'] = 'ok';
        $_SESSION['modif'] = 'ok';
        echo "<script type='text/javascript'>document.location.replace('import.php');</script>";

    }
