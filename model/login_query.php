<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 29/08/2018
 * Time: 11:50
 */

    session_start();

    /*
    * CONNEXION A ORACLE
    */
    $tns = "(DESCRIPTION =(ADDRESS_LIST =(ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.3.221)(PORT = 1522)))(CONNECT_DATA =(SERVICE_NAME = prodp)))";
    $db_username = "grhowner";
    $db_password = "grhowner";
    try{
        $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);
    }
    catch(PDOException $e){
        die ('Connexion au serveur impossible ! ');
    }

    /*
     * Vérification des paramètres de connexion
     */
    if (isset($_POST['validate']))
    {
        $pseudo = $_POST['pseudo'];
        $mdp = $_POST['mdp'];

        $check = $conn ->query("SELECT * FROM GRH_EXPORT_USERS WHERE PSEUDO = '$pseudo' AND PASSWORD = '$mdp' ");
        $checkcount = $check->fetch();
        if($checkcount != false){
            $_SESSION['id'] = $checkcount['ID'];
            $_SESSION['pseudo'] = $checkcount['PSEUDO'];
            echo "<script type='text/javascript'>document.location.replace('dashboard.php');</script>";
        }
        else{
            $erreur = 'Pseudo ou mot de passe incorrect';
        }

    }