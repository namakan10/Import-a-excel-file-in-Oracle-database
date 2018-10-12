<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 29/08/2018
 * Time: 12:03
 */
?>
<!DOCTYPE html>

<html lang="fr">
        <head>

            <meta charset="utf-8">
            <title>Import excel to database</title>

            <link rel="stylesheet" href="public/css/bootstrap.min.css">
            <link rel="stylesheet" href="public/css/style_import.css">
            <link href="public/fontawesome/css/all.css" rel="stylesheet">
            <script src="public/js/jquery-3.3.1.min.js"></script>
            <script src="public/js/bootstrap.min.js "></script>
            <script src="public/js/bootstrap-filestyle.min.js"></script>

        </head>
        <body>

            <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #FEB58A">
                <a href="dashboard.php"><img src="public/images/experco.jpg" style="height: 50px; background-color: #FEB58A"/></a>
                <form method="post" style="margin-left: 900px">
                    <ul class="nav nav-pills">
                        <li class="nav-item dropdown">
                            <button type="submit" class="nav-link dropdown-toggle btn btn-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" name="profil"><i class="fas fa-user"></i> <?php echo $_SESSION['pseudo'] ?></button>
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 40px, 0px);">
                                <a class="dropdown-item" href="edit_profil.php">Modifier le profil</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="new_correspondence.php">Ajouter une correspondance</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="correspondence_list.php">Liste des correspondances</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <button type="submit" class="nav-link btn btn-light" name="disconnect"><a href="index.php">Deconnexion <i class="fas fa-power-off"></i></a></button>
                        </li>
                    </ul>
                </form>
            </nav>

            <?php if(!isset($_SESSION['upload']) OR $_SESSION['upload'] != true){
                include 'view/upload_area.php';
            }
            ?>

            <div class="container">
                <?php if(isset($erreur)) { ?>
                    <script type="text/javascript">
                        alert("<?php echo $erreur; ?>");
                    </script>
                <?php } ?>

                <?php if(isset($update)){?>
                    <script type="text/javascript">
                        alert("<?php echo $update; ?>");
                </script>
                <?php } ?>

                <?php if(isset($_SESSION['upload']) AND $_SESSION['upload'] == true){?>
                     <h1><?php echo "RELEVE DE " .$_SESSION['PERS_PRENOM'];?></h1>
                    <br/>
                    <?php
                    include 'view/entete-modification.php' ?>

                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Numéro du projet</th>
                        <th>Description</th>
                        <th>Heures regulière</th>
                        <th>Heures supp</th>
                        <th>Heures totales</th>
                        <th></th>
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
                            <td><?php echo $donnee['DESCRIPTION']?></td>
                            <td><?php echo $donnee['HREG'] ?></td>
                            <td><?php echo $donnee['HSUPP']?></td>
                            <td><?php echo $donnee['HTOTAL']?></td>
                            <td><a class="confirmModalLink" style="color: red" href="model/delete_line.php?id=<?php echo $donnee['COL_ID'] ?>"><i class="far fa-trash-alt"></i> </a></td>
                        </tr>

                    <?php }

                    include 'model/modification.php';

                    ?>
                    </tbody>
                </table>

                <form method="post">
                    <button class="btn btn-primary" type="submit" name="import">Importer</button>
                </form>

            <?php }else{?>
                <?php
                if(isset($_POST['prenom']) AND !isset($erreur)){?>
                    <script type="text/javascript">
                        alert('<?php echo 'Toutes les données du relevé de '.$_POST['prenom'].' sont déjà dans la base de donnée';?>');
                        document.location.href = 'dashboard.php';
                    </script>
                <?php } }?>
            </p>
        </div>

        <?php include'view/modal_delete.php' ?>
    </body>
</html>