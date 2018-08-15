<?php

//Connexion à oracle
$tns = " 
        (DESCRIPTION =
            (ADDRESS_LIST =
              (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1522))
            )
            (CONNECT_DATA =
              (SERVICE_NAME = gt)
            )
          )
               ";
$db_username = "gt";
$db_password = "gt";
try{
    $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);
}catch(PDOException $e){
    echo ($e->getMessage());
}


include 'src/traitement/authentification_login.php' ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="src/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="src/bootstrap/css/style.css"/>
</head>

<body>
    <div class = "container">

        <img class = "image" src="images/Logo_Experco_horizontal_mod-Nor.jpg"/>

        <div class = "form" id = "taille">
            <form method="post" action="">
                <div class="form-group row">
                    <label for="nom_utilisateur" class="col-sm-3 col-form-label">Nom d'utilisateur</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="nom_utilisateur" name="pseudo">
                    </div>
                </div>

                 <div class="form-group row">
                     <label for="password" class="col-sm-3 col-form-label">Mot de passe</label>
                     <div class="col-sm-9">
                         <input type="password" class="form-control" id="password" name="mdp">
                     </div>
                 </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block" name="validate">Se connecter</button>
            </form>


        <?php
            if (isset($erreur)){ ?>
                <script src="src/bootstrap/js/jquery.js"></script>
                <script src="src/bootstrap/js/bootstrap.min.js "></script>
                <script>
                    $(function () {
                        alert('<?php echo $erreur ?>')
                    });
                </script>
            <?php }
            if (isset($_SESSION['error'])){ ?>
                <script src="src/bootstrap/js/jquery.js"></script>
                <script src="src/bootstrap/js/bootstrap.min.js "></script>
                <script>
                    $(function () {
                        alert("<?php echo $_SESSION['error'] ?>")
                    });
                </script>
            <?php }
        ?>
    </div>
</body>
</html>
