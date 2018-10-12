<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Edit profil</title>
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
            <h1 style="text-align: center">Modifier les données de connexion</h1>
            <br/>
            <br/>
            <form style="width: 50%; margin: auto" method="post" action="">
                <div class="form-group">
                    <label for="loginInputEmail1">Nouveau nom d'utilisateur</label>
                    <input type="text" class="form-control" id="loginInputEmail1" aria-describedby="loginHelp" name="pseudo">
                    <small id="loginHelp" class="form-text text-muted">Facultatif, laissez le champ vide pour ne pas modifier le login acteul</small>
                </div>
                <div class="form-group">
                    <label for="InputPassword1">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="InputPassword1" name="password" required>
                </div>
                <div class="form-group">
                    <label for="InputPasswordconfirm">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="InputPasswordconfirm" name="passwordconfirm" required>
                </div>
                <button type="submit" class="btn btn-primary" name="update">Mettre à jour</button>
            </form>
        </div>


        <script src="public/js/jquery-3.3.1.min.js"></script>
        <script src="public/js/bootstrap.min.js "></script>
        <?php if(isset($erreur)){ ?>
            <script>
                $(function () {
                    alert('<?php echo $erreur ?>')
                });
            </script>
        <?php }?>

        <?php if(isset($success)){ ?>
            <script>
                $(function () {
                    alert('<?php echo $success ?>')
                });
            </script>
        <?php }?>
    </body>
</html>