<?php

    session_start();
    /*
     * SI LA PERSONNE N'EST PAS AUTHENTIFIER
     */
    if(!isset($_SESSION['pseudo'])){
        $_SESSION['error'] = 'Veuillez vous connceter d\'abord';
        echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
    }


    /*
     * CONNEXION A ORACLE
     */
     $tns = "
        (DESCRIPTION =
            (ADDRESS_LIST =
              (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.3.221)(PORT = 1522))
            )
            (CONNECT_DATA =
              (SERVICE_NAME = prodp)
            )
          )
               ";

    $db_username = "grhowner";
    $db_password = "grhowner";
    try{
        $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);
    }catch(PDOException $e){
        echo ($e->getMessage());
    }

    //Recuperation de la liste des employé
    $membres = $conn->query("SELECT * FROM GRH_PERSONNE WHERE STATUT_EMPLOYE ='A' AND CAT_PERS IS NULL OR CAT_PERS = 'E' ORDER BY PRENOM");


    $_SESSION['validate'] = 'false';
    if(isset($_SESSION['modif']) OR isset($_SESSION['supprimer'])){
        $_SESSION['validate'] = 'ok';
    }

    /*
     * SI ON UPLOADE UN FICHIER
     */
    if (isset($_POST['boutton'])) {


        $string = $_FILES['file']['name'];
        $var1 = array("é", "è", "à", "ç", "Diariata", "Nene", "Seydina");
        $var2 = array("e", "e", "a", "c", "Diariata Anna", "Nene Satourou", "Seydina Oumar");
        $string = str_replace($var1, $var2, $string);

        /*
         * MESSAGE D'ERREUR POUR LE MAUVAIS CHOIX DU RELEVE
         */
        if(stristr($string, $_POST['prenom']) === FALSE) {
            $_SESSION['erreur'] = 'Le rélevé selectionné n\'est pas celui de ' .$_POST['prenom'] . ' . Veuillez recommencé' ;
        }

        /*
         * VERIFICATION SI LE NOM SELCETIONNE CORRESPOND A CELUI DU RELEVE
         */
        else{

            /*
             * SUPPRESSION DES DONNEES DES TABLES TEMPORAIRES AU CAS OU IL EN AURAI
             */
            //$conn->exec("DELETE FROM GRH_RELEVE_GT_TEMP");
            $conn->exec("DELETE FROM GRH_RELEVE_HEURE_TEMP");
            $conn->exec("DELETE FROM GRH_RELEVE_ACTIVITE_TEMP");


            /*
             * RECUPERATION DU PERS_ID CORREPODAND AU PRENOM DONNE
             */
            $query = $conn->query('SELECT * FROM GRH_PERSONNE WHERE STATUT_EMPLOYE = \'A\' AND PRENOM = \''.$_POST['prenom'].'\' ');
            while ($one = $query->fetch()) {
                $pers = $one['PERS_ID'];
            }
            $_SESSION['PERS_ID'] = $pers;
            $_SESSION['PERS_PRENOM'] = $_POST['prenom'];

            /*
             * CONVERTION DU FICHIER EN CSV
             */
            $file = $_FILES['file']['tmp_name'];
            include "convert.php";



            /*
             * SUPPRESION DE LA PREMIERE LIGNE DU FICHIER
             */
            $handle_newfile = fopen('temp/temp.csv', "w");
            $handle = fopen('temp/temp0.csv', "r");
            $header = TRUE;
            while (($buffer = fgets($handle, 4096)) !== false) {
                if ($header !== TRUE) {
                    fwrite($handle_newfile, $buffer);
                }
                else {
                    $header = FALSE;
                }
            }
            fclose($handle);
            fclose($handle_newfile);




            /*
             * INSERTION DES FICHIERS DANS LES TABLES TEMPORARIRES RELEVE ACTIVITE ET GRH_RELEVE_HEURE
             */
            $autorisation = true;
            $handle = fopen('temp/temp.csv', "r");
            $_SESSION['effectue'] = 'non';
            include 'src/query/insert/insert_grh_releve_activite.php';
            fclose($handle);
            $handle = fopen('temp/temp.csv', "r");
            include 'src/query/insert/insert_grh_releve_heure.php';
        }
    }

    /*
     * INSERTIONS DANS LES TABLES DEFINITIVES APRES TOUTES LES VERIFICATIONS ET MODIFICATION
     */
    if(isset($_POST['import'])){

        include 'src/query/insert/insert_grh_releve_gt.php';
        include 'src/query/insert/final_insert.php';
        ?>
    <script type="text/javascript">
        alert('Les données ont été inseré avec success ! ');
        document.location.href = 'import.php';
    </script>
   <?php }?>