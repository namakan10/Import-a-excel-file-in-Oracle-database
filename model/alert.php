<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 29/08/2018
 * Time: 13:18
 */

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