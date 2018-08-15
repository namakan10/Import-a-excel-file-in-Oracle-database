<?php
    session_start();

    /*
     * SI LA PERSONNE N'EST PAS AUTHENTIFIER
     */
    if(!isset($_SESSION['pseudo'])){
        $_SESSION['error'] = 'Veuiilez vous connceter d\'abord';
        echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
    }

    if(isset($_POST['disconnect'])){

        $_SESSION = array();
        session_destroy();
        echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
    }

    include 'src/traitement/update_login.php'

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Edit profil</title>
        <link rel="stylesheet" href="src/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="src/bootstrap/css/style.css"/>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="import.php" style="color: red">EXPERCO INTERNATIONAL</a>
            <form method="post" style="margin-left: 900px">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-light" name="profil"><?php echo $_SESSION['pseudo'] ?></button>
                    <button type="submit" class="btn btn-light" name="disconnect"><a href="index.php">Deconnexion</a></button>
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
            <script src="src/bootstrap/js/jquery-3.3.1.min.js"></script>
            <script src="src/bootstrap/js/bootstrap.min.js "></script>
            <script>
                $(function () {
                    alert('<?php echo $erreur ?>')
                });
            </script>
        <?php }?>

        <?php if(isset($success)){ ?>
            <script src="src/bootstrap/js/jquery-3.3.1.min.js"></script>
            <script src="src/bootstrap/js/bootstrap.min.js "></script>
            <script>
                $(function () {
                    alert('<?php echo $success ?>')
                });
            </script>
        <?php }?>

    </body>
</html>