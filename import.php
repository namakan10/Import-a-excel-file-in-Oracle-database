<?php

    include 'import_sql.php';

    if(isset($_SESSION ['erreur'])){
        if($_SESSION ['erreur'] != null){
            $erreur = $_SESSION ['erreur'];
            $_SESSION ['erreur'] = null;
        }
    }

    if(isset($_SESSION ['update'])){
        if($_SESSION ['update'] != null){
            $update = $_SESSION ['update'];
            $_SESSION ['update'] = null;
        }
    }

    if(isset($_SESSION ['erreuraffec'])){
        if($_SESSION ['erreuraffec'] != null){
            $erreuraffec = $_SESSION ['erreuraffec'];
            $_SESSION ['erreuraffec'] = null;
        }
    }

    if(isset($_POST['disconnect'])){

        $_SESSION = array();
        session_destroy();
        echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
    }

    if(isset($_POST['profil'])){
        echo "<script type='text/javascript'>document.location.replace('edit_profil.php');</script>";
    }
?>
<!DOCTYPE html>

<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Import Excel To Mysql Database Using PHP </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Import Excel File To MySql Database Using php">

        <link rel="stylesheet" href="src/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="src/bootstrap/css/style_import.css">
        <script src="src/bootstrap/js/jquery.js"></script>
        <script src="src/bootstrap/js/bootstrap.min.js "></script>

    </head>
    <body>


        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#" style="color: red">EXPERCO INTERNATIONAL</a>
            <form method="post" style="margin-left: 850px">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-light" name="profil"><?php echo $_SESSION['pseudo'] ?></button>
                    <button type="submit" class="btn btn-light" name="disconnect"><a href="index.php">Deconnexion</a></button>
                </div>
            </form>
        </nav>
        <?php if(!isset($_SESSION['effectue']) OR $_SESSION['effectue'] != 'succes'){
            include 'src/view/nav_bar.php';
        }
        ?>
        <div class="container">
            <?php if(isset($erreur)) { ?>
                <script type="text/javascript">
                    alert("<?php echo $erreur; ?>");
                    document.location.href = 'import.php';
                </script>
            <?php } ?>

            <?php if(isset($update)){?>
                <script type="text/javascript">
                    alert("<?php echo $update; ?>");
                    document.location.href = 'import.php';
                </script>
            <?php } ?>

            <?php if(isset($_SESSION['effectue']) AND $_SESSION['effectue'] == 'succes'){?>
                <h1><?php echo "RELEVE DE " .$_SESSION['PERS_PRENOM'];?></h1>
                <br/>
                <?php
            include 'src/view/entete-modification.php' ?>
                <?php if(isset($erreuraffec)){?> <p style="text-align: center; color: red"> <?php echo $erreuraffec;} ?> </p>

            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Numéro du projet</th>
                        <th>Phase</th>
                        <th>Description</th>
                        <th>Heures regulière</th>
                        <th>Heures supplementaires</th>
                        <th>Heures totales</th>
                        </tr>
                </thead>
                <tbody>
                    <?php
                        $req = $conn->query('SELECT DISTINCT * FROM GRH_RELEVE_HEURE_TEMP, GRH_RELEVE_ACTIVITE_TEMP WHERE GRH_RELEVE_HEURE_TEMP.COL_ID = GRH_RELEVE_ACTIVITE_TEMP.COL_ID AND GRH_RELEVE_ACTIVITE_TEMP.PERS_ID = \''. $_SESSION['PERS_ID'].'\' ORDER BY GRH_RELEVE_ACTIVITE_TEMP.COL_ID ');
                        while ($donnee = $req->fetch()){?>

                            <tr>
                                <td><?php echo $donnee['COL_ID'] ?></td>
                                <td><?php echo $donnee['DATE_JOUR'] ?></td>
                                <td><?php echo $donnee['NO_PROJ']?></td>
                                <td><?php echo $donnee['PHASE'];?></td>
                                <td><?php echo $donnee['DESCRIPTION']?></td>
                                <td><?php echo $donnee['HREG'] ?></td>
                                <td><?php echo $donnee['HSUPP']?></td>
                                <td><?php echo $donnee['HTOTAL']?></td>
                            </tr>

                        <?php }
                     $valide = 'false';   ?>


                    <?php
                    include 'src/query/modification/modification.php';
                    include 'src/query/modification/suppression.php';
                     ?>
                </tbody>
                    </table>

                <form method="post">
                    <button class="btn btn-primary" type="submit" name="import">Importer</button>
                </form>

            <?php }else{?>
            <p style="color: green; text-align: center">
                <?php
                    if(isset($erreuraffec) AND isset($_POST['prenom'])){?> <p style="text-align: center; color: red"> <?php echo $_POST['prenom'] ." n'est affceté à aucun projet ou phase de projet du relevé";}
                        else if(isset($_POST['prenom']) AND !isset($erreur)){?>
                            <script type="text/javascript">
                                alert('<?php echo 'Toutes les données du relevé de '.$_POST['prenom'].' sont déjà dans la base de donnée';?>');
                                document.location.href = 'import.php';
                            </script>
                <?php } }?>
            </p>
        </div>
    </body>
</html>