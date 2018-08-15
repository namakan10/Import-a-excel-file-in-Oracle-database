<?php

    /*
    * CONNEXION A ORACLE
    */
    $tns = "(DESCRIPTION =(ADDRESS_LIST =(ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.3.221)(PORT = 1522)))(CONNECT_DATA =(SERVICE_NAME = prodp)))";
    $db_username = "grhowner";
    $db_password = "grhowner";
    try{
        $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);
    }catch(PDOException $e){
        echo ($e->getMessage());
    }

    if (isset($_POST['update']))
    {

        if (!empty($_POST['pseudo']) AND !empty($_POST['password']) AND !empty($_POST['passwordconfirm'])){
            $pseudo = $_POST['pseudo'];
            $mdp = $_POST['password'];
            $mdp1 = $_POST['passwordconfirm'];

            if($mdp1 == $mdp){
                $check = $conn ->prepare('UPDATE GRH_EXPORT_USERS SET PSEUDO = :pseudo, PASSWORD = :mdp WHERE PSEUDO = :oldpseudo ');
                $check->execute(array(
                    'pseudo' => $pseudo,
                    'mdp' => $mdp,
                    'oldpseudo' => $_SESSION['pseudo']
                ));
                $_SESSION['pseudo'] = $pseudo;
                $success = "Mise à jour effectuée !";
            }
            else{
                $erreur = 'Les deux mots de passe ne correspondent pas !';
            }

        }
        else if(!empty($_POST['password']) AND !empty($_POST['passwordconfirm'])){
            $mdp = $_POST['password'];
            $mdp1 = $_POST['passwordconfirm'];

            if($mdp1 == $mdp){
                $check = $conn ->prepare('UPDATE GRH_EXPORT_USERS SET PASSWORD = :mdp WHERE PSEUDO = :oldpseudo ');
                $check->execute(array(
                    'mdp' => $mdp,
                    'oldpseudo' => $_SESSION['pseudo']
                ));
                $success = "Mise à jour effectuée !";
            }
            else{
                $erreur = 'Les deux mots de passe ne correspondent pas !';
            }
        }
        else{
            $erreur = 'Veuillez remplir au moins les deux champs de mot de passe';
        }

    }