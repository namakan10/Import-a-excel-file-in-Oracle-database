<?php

    require 'model/insert_in_temp_table.php';

    require 'model/alert.php';



    if(isset($_POST['disconnect'])){

        $_SESSION = array();
        session_destroy();
        echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
    }

    if(isset($_POST['profil'])){
        echo "<script type='text/javascript'>document.location.replace('edit_profil.php');</script>";
    }

    require 'view/dashboardView.php';


