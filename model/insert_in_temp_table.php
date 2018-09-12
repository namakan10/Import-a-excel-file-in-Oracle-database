<?php
    /**
     * Created by PhpStorm.
     * User: Ghost
     * Date: 29/08/2018
     * Time: 13:12
     */

    session_start();


    /*
     * VERIFICATION SI L'UTILISATEUR EST AUTHENTIFIE
     */
    if(!isset($_SESSION['pseudo'])){
        $_SESSION['error'] = 'Veuillez vous connceter d\'abord';
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
    }
    catch(PDOException $e){
        die ('Connexion au serveur impossible ! ');
    }



    /*
     * RECUPERATION DE LA LISTE DES EMPLOYES
     */
    $membres = $conn->query("SELECT PRENOM, NOM FROM GRH_PERSONNE WHERE STATUT_EMPLOYE ='A' AND CAT_PERS IS NULL OR CAT_PERS = 'E' ORDER BY NOM");


    $_SESSION['validate'] = 'false';
    if(isset($_SESSION['modif']) OR isset($_SESSION['supprimer'])){
        $_SESSION['validate'] = 'ok';
    }


    /*
     * TRAITEMENT APRES L'UPLOAD DU FICHIER
     */
    if (isset($_POST['boutton'])) {


        /*
         * VERIFICATION SI LE BON RELEVE A ETE SELECTIONNE
         */

        //Les prenoms composés
        $string = $_FILES['file']['name'];
        $var1 = array("é", "è", "à", "ç", "Nene", "Diariata");
        $var2 = array("e", "e", "a", "c", "NENE SATOUROU", "DIARIATA ANNA");
        $string = str_replace($var1, $var2, $string);


        if(stristr($string, $_POST['prenom']) === FALSE) {
            $_SESSION['erreur'] = 'Le rélevé selectionné n\'est pas celui de ' .$_POST['prenom'] . ' . Veuillez recommencer' ;
        }


        else{

            /*
             * SUPPRESSION DES DONNEES DES TABLES TEMPORAIRES AU CAS OU IL EN AURAI
             */
            $conn->exec("DELETE FROM GRH_RELEVE_HEURE_TEMP");
            $conn->exec("DELETE FROM GRH_RELEVE_ACTIVITE_TEMP");


            /*
             * DECOUPAGE DU NOM ET DU PRENOM
             */
            $nom = explode(' ', $_POST['prenom'])[0];
            $prenom1 = explode(' ', $_POST['prenom'])[1];
            $prenom = null;
            if(isset(explode(' ', $_POST['prenom'])[2]) ){
                $prenom = explode(' ', $_POST['prenom'])[2];
                $prenom = $prenom1.' '.$prenom;
            }
            else{
                $prenom = $prenom1;
            }


            /*
             * RECUPERATION DU PERS_ID CORREPODAND AU PRENOM DONNE
             */
            $query = $conn->query('SELECT * FROM GRH_PERSONNE WHERE STATUT_EMPLOYE = \'A\' AND PRENOM = \''.$prenom.'\' AND NOM = \''.$nom.'\' ');
            while ($one = $query->fetch()) {
                $pers = $one['PERS_ID'];
            }
            $_SESSION['PERS_ID'] = $pers;
            $_SESSION['PERS_PRENOM'] = $_POST['prenom'];


            /*
             * CONVERTION DU FICHIER EN CSV
             */
            $file = $_FILES['file']['tmp_name'];
            require "model/convert_xlsm_to_csv.php";



            /*
             * INSERTION DES FICHIERS DANS LES TABLES TEMPORARIRES RELEVE ACTIVITE ET GRH_RELEVE_HEURE
             */
            $autorisation = true;
            $handle = fopen('public/temp/temp.csv', "r");
            $_SESSION['effectue'] = 'non';
            include 'model/insert_grh_releve_activite_&_heure.php';
            fclose($handle);

        }


    }

        /*
        * INSERTIONS DANS LES TABLES DEFINITIVES APRES TOUTES LES VERIFICATIONS ET MODIFICATION
        */
        if(isset($_POST['import'])){

            include 'model/final_insert.php';
            ?>
            <script type="text/javascript">
                alert('Les données ont été inseré avec success ! ');
                document.location.href = 'dashboard.php';
            </script>
        <?php } ?>