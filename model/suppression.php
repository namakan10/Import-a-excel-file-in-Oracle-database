<?php
    if(isset($_POST['supprimer'])){


        $id = $_POST['id_supp'];

        $succes = false;
        $query = $conn ->query("SELECT * FROM GRH_RELEVE_HEURE_TEMP WHERE COL_ID = '$id' ");

        if($query != false){
          $succes = true;
        }


        $query = $conn ->prepare('DELETE FROM GRH_RELEVE_HEURE_TEMP WHERE COL_ID = :id  AND NO_PROJ=:proj');
        $query->execute(array(
            'id' => $id,
        ));


        $query = $conn ->prepare('DELETE FROM GRH_RELEVE_ACTIVITE_TEMP WHERE COL_ID = :id');
        $req = $query->execute(array(
            'id' => $id,
        ));


        if($succes == true){
            $_SESSION['update'] = 'Champ supprimer avec succès !';
        }
        else{
            $_SESSION['erreur'] = 'Champ inexistant ! ';
        }

        $_SESSION['validate'] = 'ok';
        $_SESSION['supprimer'] = 'ok';

        echo "<script type='text/javascript'>document.location.replace('dashboard.php');</script>";
    }