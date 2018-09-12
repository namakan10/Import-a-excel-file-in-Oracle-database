<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Edit profil</title>
        <link rel="stylesheet" href="public/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="public/css/style.css"/>
    </head>

    <body>

        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #FEB58A">
            <a class="navbar-brand" href="dashboard.php" style="color: red"><img src="public/images/experco.jpg" style="height: 50px; background-color: #FEB58A"/></a>
            <form method="post" style="margin-left: 950px">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-light" name="profil"><i class="fas fa-user"></i> <?php echo $_SESSION['pseudo'] ?></button>
                    <button type="submit" class="btn btn-light" name="disconnect"><a href="index.php">Deconnexion <i class="fas fa-power-off"></i></a></button>
                </div>
            </form>
        </nav>

        <div class = "container">
            <br/>
            <h1 style="text-align: center">Modifier les données de connexion</h1>
            <br/>
            <br/>
            <form style="width: 50%; margin: auto" method="post" action="">
                <div class="form-group">
                    <label for="loginInputEmail1">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="loginInputEmail1" aria-describedby="loginHelp" name="pseudo">
                    <small id="loginHelp" class="form-text text-muted">Facultatif, laissez le champ vide pour ne pas modifier le login acteul</small>
                </div>
                <div class="form-group">
                    <label for="InputPassword1">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="InputPassword1" name="password">
                </div>
                <div class="form-group">
                    <label for="InputPasswordconfirm">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="InputPasswordconfirm" name="passwordconfirm">
                </div>
                <button type="submit" class="btn btn-primary" name="update">Mettre à jour</button>
            </form>
        </div>


        <?php if(isset($erreur)){ ?>
            <script src="public/js/jquery-3.3.1.min.js"></script>
            <script src="public/js/bootstrap.min.js "></script>
            <script>
                $(function () {
                    alert('<?php echo $erreur ?>')
                });
            </script>
        <?php }?>

        <?php if(isset($success)){ ?>
            <script src="public/js/jquery-3.3.1.min.js"></script>
            <script src="public/js/bootstrap.min.js "></script>
            <script>
                $(function () {
                    alert('<?php echo $success ?>')
                });
            </script>
        <?php }?>

    </body>
</html>