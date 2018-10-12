<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 08/10/2018
 * Time: 10:43
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


    if(isset($_POST['add'])){
        $rel = $_POST['releve'];
        $base = $_POST['base'];
        if(!empty($rel) AND !empty($base)){
            $req = $conn->query("SELECT * FROM GRH_AMIOS001 WHERE NO_PROJ = '$rel'");
            if($req->fetch() != false){
                $erreur = "La correpondance au projet ".$rel." existe déjà. Correpondant : AMIOS001";
            }
            else{
                $req = $conn->query("SELECT * FROM GRH_AADDA001 WHERE NO_PROJ = '$rel'");
                if($req->fetch() != false){
                    $erreur = "La correpondance au projet ".$rel." existe déjà. Correpondant : AADDA001";
                }
                else{
                    if($base == "AMIOS001"){
                        $req = $conn->exec("INSERT INTO GRH_AMIOS001(NO_PROJ) VALUES('$rel')");
                    }
                    else{
                        $req = $conn->exec("INSERT INTO GRH_AADDA001(NO_PROJ) VALUES('$rel')");
                    }
                    $success = "Ajouter avec succes !";
                }
            }
        }
    }