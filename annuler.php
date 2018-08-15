<?php

    session_start();

    if(!isset($_SESSION['pseudo'])){
        $_SESSION['erreur'] = 'Veuiilez vous connceter d\'abord';
        echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
    }


    //Connexion à oracle
    $tns = "(DESCRIPTION =(ADDRESS_LIST =(ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.3.221)(PORT = 1522)))(CONNECT_DATA =(SERVICE_NAME = prodp)))";
    $db_username = "grhowner";
    $db_password = "grhowner";
    try{
        $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);
    }catch(PDOException $e){
        echo ($e->getMessage());
    }

    $conn->exec("DELETE FROM GRH_RELEVE_GT_TEMP");

    $conn->exec("DELETE FROM GRH_RELEVE_HEURE_TEMP");

    $conn->exec("DELETE FROM GRH_RELEVE_ACTIVITE_TEMP");
    $_SESSION['effectue'] = 'non';

    echo "<script type='text/javascript'>document.location.replace('import.php');</script>";