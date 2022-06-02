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

    print("$title<br>$description<br>$methode_cuisson<br>$etape<br>$temps_realisation<br>$difficulte<br>$type_recette<br>$recette_image");

    $sql = "INSERT INTO recette (id, nom, description, date_publication, etape, temps_realisation, ingredients, illustration, methode_cuisson, auteur, type, difficulte) VALUES (30,'titre','description','2022-05-10','etape','10','ouais','recette_image','Marinade','moi','type_recette','facile');";
    $insert_sql = $bdd->prepare($sql);
    $insert_sql->execute();

    //probleme sur l'acquisition des valeurs POST mais liaison avec la bdd ok 
?>