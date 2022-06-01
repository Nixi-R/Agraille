<?php
    session_start();
    $bdd = new PDO ('mysql:host=localhost;dbname=agrailledb;charset=utf8','root','', [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1 COLLATE latin1_general_ci",PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $title = $_POST["title"];
    $description = $_POST["description"];
    $methode_cuisson = $_POST["methode_cuisson"];
    $etape = $_POST["etape"]; //modifier avec newInput.name
    $temps_realisation = $_POST["temps_realisation"];
    $difficulte = $_POST["difficulte"];
    $type_recette = $_POST["type_recette"];
    $recette_image = $_POST["recette_image"];

    $sql = 'INSERT INTO recette(nom,description,date_publication,etape,temps_realisation,ingredients,illustration,methode_cuisson,auteur,type,difficulte) VALUES ('$title','$description',/*date*/,'$etape','$temps_realisation',/*ingredient*/,'$recette_image','$methode_cuisson',/*auteur*/,'$type_recette','$dificulte')';
    $insert_sql = $bdd->prepare($sql);
    $insert_sql->execute();
?>