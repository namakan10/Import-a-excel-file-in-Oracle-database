<?php
    session_start();

        if(!isset($_SESSION['pseudo'])){
            $_SESSION['error'] = 'Veuiilez vous connceter d\'abord';
            echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
        }

        if(isset($_POST['disconnect'])){

            $_SESSION = array();
            session_destroy();
            echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
        }

        /*
         * CONNEXION A ORACLE
         */
        $tns = "(DESCRIPTION =(ADDRESS_LIST =(ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.3.221)(PORT = 1522)))(CONNECT_DATA =(SERVICE_NAME = prodp)))";
        $db_username = "grhowner";
        $db_password = "grhowner";
        try{
            $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);
        }catch(PDOException $e){
            die("Impossible de se connecter au serveur !");
        }


        $id = $_GET['id'];

        $succes = false;
        $query = $conn ->query("SELECT * FROM GRH_RELEVE_HEURE_TEMP WHERE COL_ID = '$id' ");

        if($query != false){
          $succes = true;
        }


        $query = $conn ->prepare('DELETE FROM GRH_RELEVE_HEURE_TEMP WHERE COL_ID = :id');
        $query->execute(array(
            'id' => $id,
        ));


        $query = $conn ->prepare('DELETE FROM GRH_RELEVE_ACTIVITE_TEMP WHERE COL_ID = :id');
        $req = $query->execute(array(
            'id' => $id,
        ));


        if($succes == true){
            $_SESSION['update'] = 'Ligne supprimer avec succès !';
        }
        else{
            $_SESSION['erreur'] = 'Champ inexistant ! ';
        }

        echo "<script type='text/javascript'>document.location.replace('../dashboard.php');</script>";