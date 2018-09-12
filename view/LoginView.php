<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8"/>
        <title>Login</title>
        <link rel="stylesheet" href="public/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="public/css/login_style.css"/>
        <script src="public/js/jquery.js"></script>
        <script src="public/js/bootstrap.min.js "></script>
    </head>

    <body>

        <section class="login-block">
            <div class="container">
                <div class="">
                    <div class="banner-sec">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active">
                                    <img class="d-block img-fluid pos" src="public/images/Logo_Experco_horizontal_mod-Nor.jpg" alt="First slide">
                                    <div class="carousel-caption d-none d-md-block">

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-8 login-sec">
                        <h2 class="text-center">Identifiez-vous</h2>
                        <form class="login-form" method="post">
                            <div class="form-group row">
                                <label for="exampleInputEmail1" class="text-uppercase col-sm-3 col-form-label" >Pseudo</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="" name="pseudo" required>
                                </div>
                           </div>
                            <div class="form-group row">
                                <label for="exampleInputPassword1" class="text-uppercase  col-sm-3 col-form-label">Mot de passe</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" placeholder="" name="mdp" required>
                                </div>
                            </div>
                            <div class="form-check">
                                <button type="submit" class="btn btn-login float-right" name="validate">Se connecter</button>
                            </div>

                        </form>
                        <br/>
                    </div>

                </div>
        </section>

            <?php
            if (isset($erreur)){ ?>
                <script src="../public/js/jquery.js"></script>
                <script src="../public/js/bootstrap.min.js "></script>
                <script>
                    $(function () {
                        alert('<?php echo $erreur ?>')
                    });
                </script>
            <?php }
            if (isset($_SESSION['error'])){ ?>
                <script src="../public/js/jquery.js"></script>
                <script src="../public/js/bootstrap.min.js "></script>
                <script>
                    $(function () {
                        alert("<?php echo $_SESSION['error'] ?>")
                    });
                </script>
            <?php }
            ?>
    </body>
</html>
