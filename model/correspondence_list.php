<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 08/10/2018
 * Time: 11:31
 */

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

    $req = $conn->query("SELECT * FROM GRH_AMIOS001");

    $req1 = $conn->query("SELECT * FROM GRH_AADDA001");