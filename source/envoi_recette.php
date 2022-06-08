<?php
    session_start();
    $bdd = new PDO ('mysql:host=localhost;dbname=agrailledb;charset=utf8','root','', [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1 COLLATE latin1_general_ci",PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    switch (null) {
        case $_POST["title"]:
            echo "<h5>Erreur: Vous n'avez pas entré de titre pour votre recette.</h5><a href = http://localhost/Agraille/source/redaction_recette.php>Retourner à la page de création</a>";
            break;
        case $_POST["description"]:
            echo "<h5>Erreur: Vous n'avez pas entré de description pour votre recette.</h5><a href = http://localhost/Agraille/source/redaction_recette.php>Retourner à la page de création</a>";
            break;
        case $_POST["methode_cuisson"]:
            echo "<h5>Erreur: Vous n'avez pas entré de méthode de cuisson pour votre recette.</h5><a href = http://localhost/Agraille/source/redaction_recette.php>Retourner à la page de création</a>";
            break;
        case $_POST["step_1"]:
            echo "<h5>Erreur: Vous n'avez pas entré d'étapes pour votre recette.</h5><a href = http://localhost/Agraille/source/redaction_recette.php>Retourner à la page de création</a>";
            break;
        case $_POST["temps_realisation"]:
            echo "<h5>Erreur: Vous n'avez pas entré de temps de réalisation pour votre recette.</h5><a href = http://localhost/Agraille/source/redaction_recette.php>Retourner à la page de création</a>";
            break;
        case $_POST["difficulte"]:
            echo "<h5>Erreur: Vous n'avez pas entré de difficulté pour votre recette.</h5><a href = http://localhost/Agraille/source/redaction_recette.php>Retourner à la page de création</a>";
            break;
        case $_POST["type_recette"]:
            echo "<h5>Erreur: Vous n'avez pas entré de type de recette pour votre recette.</h5><a href = http://localhost/Agraille/source/redaction_recette.php>Retourner à la page de création</a>";
            break;
        case $_POST["recette_image"]:
            echo "<h5>Erreur: Vous n'avez pas entré d'illustration pour votre recette.</h5><a href = http://localhost/Agraille/source/redaction_recette.php>Retourner à la page de création</a>";
            break;
    }

    $title = $_POST["title"];
    $description = $_POST["description"];
    $methode_cuisson = $_POST["methode_cuisson"];
    $etape = array();
    $temps_realisation = $_POST["temps_realisation"];
    $difficulte = $_POST["difficulte"];
    $type_recette = $_POST["type_recette"];
    $recette_image = $_POST["recette_image"];
    $date = date("Y-m-d");
    $auteur = $_SESSION["pseudo"];

    for ($i = 1; isset($_POST["step_$i"]); $i++) {
        array_push($etape, "$i-".$_POST["step_$i"]);
    }

    $str_etape = implode(". ", $etape);

    $sql = "INSERT INTO recette (id, nom, description, date_publication, etape, temps_realisation, ingredients, illustration, methode_cuisson, auteur, type, difficulte) VALUES (30,'$title','$description','$date','$str_etape','$temps_realisation','ouais','$recette_image','$methode_cuisson','$auteur','$type_recette','$difficulte');";
    $insert_sql = $bdd->prepare($sql);
    $insert_sql->execute();

    print("Nous avons reçu votre recette !");

?>