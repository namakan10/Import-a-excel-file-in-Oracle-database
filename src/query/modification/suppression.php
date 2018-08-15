<?php
    if(isset($_POST['supprimer'])){


        //RECUPERE LA DATE DU CHAMP A MODIFIER
        $dateidentifie = $_POST['date_supp'];
        $query = $conn->query("SELECT to_date('$dateidentifie','dd/mm/rrrr') FROM DUAL");
        $date = $query->fetch();
        $dt = $date[0];
        $proj = $_POST['proj_supp'];

        $query = $conn ->query("SELECT * FROM GRH_RELEVE_HEURE_TEMP WHERE DATE_JOUR = '$dt'  AND NO_PROJ= '$proj'");
        $donne = $query->fetch();

        $query = $conn ->prepare('DELETE FROM GRH_RELEVE_HEURE_TEMP WHERE DATE_JOUR = :dates  AND NO_PROJ=:proj');
        $query->execute(array(
            'dates' => $dt,
            'proj' => $_POST['proj_supp']
        ));


        $query = $conn ->prepare('DELETE FROM GRH_RELEVE_ACTIVITE_TEMP WHERE DATE_JOUR = :dates  AND NO_PROJ=:proj');
        $req = $query->execute(array(
            'dates' => $dt,
            'proj' => $_POST['proj_supp']
        ));


        if($donne == true){
            $_SESSION['update'] = 'Champ supprimer avec success !';
        }
        else{
            $_SESSION['erreur'] = 'Champ inexistant ! ';
        }

        $_SESSION['validate'] = 'ok';
        $_SESSION['supprimer'] = 'ok';
        echo "<script type='text/javascript'>document.location.replace('import.php');</script>";
    }