<?php
    if(isset($_POST['modifier'])){



        /*
         * RECUPERATION DE L'ID et de MODIFICATION à faire
         */

        $id = $_POST['id'];
        $modif = $_POST['modif'];

        if(intval($id) <= 0 ){
            $_SESSION['update'] = "L'id du champ n'est pas valide";
        }
        else{
            $req = $conn->query("SELECT * FROM GRH_RELEVE_HEURE_TEMP WHERE COL_ID = '$id' ");
            if($req->fetch() == null){
                $_SESSION['update'] = "Le champ sélectionné n'existe pas !";
            }
            else{

                //MODIFICATION DE LA DATE
                if ($_POST['select']=='Date'){


                    function validateDate($date, $format = 'd/m/y')
                    {
                        $d = DateTime::createFromFormat($format, $date);
                        return $d && $d->format($format) == $date;
                    }

                    if(validateDate($modif) == true){

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

                        $_SESSION['update'] = "Modification effectué avec succès !";
                    }

                    else{
                        $_SESSION['update'] = "Le format de date est invalide ! Il doit être de la forme jj/mm/AA";
                        echo "<script type='text/javascript'>document.location.replace('dashboard.php');</script>";
                    }
                }

                //MODIFICATION DU NUMERO PROJET
                else if ($_POST['select']=='Numéro de projet'){
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
                        $_SESSION['update'] = "Modification effectué avec succès !";
                    }
                    else{
                        $_SESSION ['erreur'] = $_SESSION['PERS_PRENOM']. " n'est pas affecté au projet ".$_SESSION['modif']." ou le projet n'existe pas";
                    }



                }

                //MODIFICATION DE LA DESCRIPTION
                else if ($_POST['select']=='Description'){
                    $query = $conn ->prepare('UPDATE GRH_RELEVE_ACTIVITE_TEMP SET DESCRIPTION = :modif WHERE COL_ID = :id');
                    $query->execute(array(

                        'modif' => $_POST['modif'],
                        'id' => $id,
                    ));
                    $_SESSION['update'] = "Modification effectué avec succès !";
                }

                //MODIFICATION DES HEURES REGULIERES
                else if ($_POST['select']=='Heures regulières'){


                    if($_POST['modif']<=8){

                        $query = $conn->query("SELECT HSUPP FROM GRH_RELEVE_HEURE_TEMP WHERE COL_ID = '$id'");

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
                        $_SESSION['update'] = "Modification effectué avec succès !";
                    }

                    else{
                        $_SESSION ['erreur'] = "L'heure regulière ne depasse pas 8 heures";
                    }
                }

                //MODIFICATION DE L'HEURE SUPPLEMENTAIRES
                else if ($_POST['select']=='Heures supplementaires'){
                    $query = $conn->query("SELECT HREG FROM GRH_RELEVE_HEURE_TEMP WHERE COL_ID = '$id'");
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
                    $_SESSION['update'] = "Modification effectué avec succès !";
                }

            }
        }


        echo "<script type='text/javascript'>document.location.replace('dashboard.php');</script>";

    }
