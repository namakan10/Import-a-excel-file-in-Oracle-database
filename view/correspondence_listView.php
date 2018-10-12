<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Liste des correspondances</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="public/css/style.css"/>
    <link rel="stylesheet" href="public/fontawesome/css/all.css">
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

        <div class = "container">
            <br/>
            <h1>Liste des correspondances</h1>
            <br/>
            <br/>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Numéro du projet sur le relvé</th>
                    <th scope="col">Numéro du projet dans la base</th>
                </tr>
                </thead>
                <tbody>

                <?php while ($donne = $req->fetch()){ ?>
                    <tr class="">
                        <td><?php echo $donne["NO_PROJ"] ?></td>
                        <td>AMIOS001</td>
                        <td><a class="confirmModalLink" href="model/delete_correspondance.php?num=<?php echo $donne["NO_PROJ"] ?>" style="color: red"><i class="far fa-trash-alt"></i></a></td>
                    </tr>
                <?php } ?>

                <?php while ($donnee = $req1->fetch()){ ?>
                    <tr>
                        <td><?php echo $donnee["NO_PROJ"] ?></td>
                        <td>AADD001</td>
                        <td><a class="confirmModalLink" href="model/delete_correspondance.php?num=<?php echo $donnee["NO_PROJ"] ?>" style="color: red"><i class="far fa-trash-alt"></i></a></td>
                    </tr>
                <?php }?>

                </tbody>
            </table>
        </div>

        <div class="modal hide fade" id="confirmModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Confirmation de la suppression</h3>
                    </div>
                    <div class="modal-body">
                        <p>Etes-vous sûr de vouloir supprimer cet élément ?</p>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-danger" id="confirmModalNo">Non</a>
                        <a href="#" class="btn btn-success" id="confirmModalYes">Oui</a>
                    </div>
                </div>
            </div>
        </div>


        <script src="public/js/jquery-3.3.1.min.js"></script>
        <script src="public/js/bootstrap.min.js "></script>
        <script>
            $(document).ready(function () {
                var theHREF;

                $(".confirmModalLink").click(function(e) {
                    e.preventDefault();
                    theHREF = $(this).attr("href");
                    $("#confirmModal").modal("show");
                });

                $("#confirmModalNo").click(function(e) {
                    $("#confirmModal").modal("hide");
                });

                $("#confirmModalYes").click(function(e) {
                    window.location.href = theHREF;
                });
            });
        </script>
        <?php if(isset($_SESSION['deletecorresp']) AND $_SESSION['deletecorresp'] != null){ ?>
            <script>
                $(function () {
                    alert('<?php echo $_SESSION['deletecorresp'] ?>')
                });
            </script>
        <?php  $_SESSION['deletecorresp'] = null;}?>

        <?php if(isset($success)){ ?>
            <script>
                $(function () {
                    alert('<?php echo $success ?>')
                });
            </script>
        <?php }?>
    </body>
</html>
