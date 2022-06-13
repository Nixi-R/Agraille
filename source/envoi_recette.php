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
    $idRecette = random_int(0, 2147483647);
    $ingredient = array();


    for($i = 1; isset($_POST["ingredient_$i"]); $i++){
        $ingredient[$i] = $_POST["quantite_$i"]  +" "+ $_POST["mesure_$i"]+" "+ $_POST["ingredient_$i"];
    }
    $ingredient = implode(". ", $ingredient);

    for($i = 1; isset($_POST["ingredient_prop_$i"]); $i++){
        $ingredient_prop = $_POST["ingredient_prop_$i"];
        $idIngredient = random_int(0, 2147483647);
        $ingredient_sql = $bdd->prepare("INSERT INTO ingredient (id,ingredient,valider) VALUES($idIngredient,$ingredient_prop,0)");
        $ingredient_sql->execute();
    }

    $str_ingredient = implode(". ", $ingredient_prop);

    for ($i = 1; isset($_POST["step_$i"]); $i++) {
        array_push($etape, $_POST["step_$i"]);
    }

    $str_etape = implode(". ", $etape);

    for($i = 0; $i>count($ingredient_prop); $i++){
        $prop = $ingredient_prop[$i];
        $idIngredient = random_int(0, 2147483647);;
        $ingredientRecetteSql = $bdd->prepare("INSERT INTO recette(id_ingredient)  WHERE id = $idRecette  VALUES($idIngredient)")
        $ingredient_sql = $bdd->prepare("INSERT INTO ingredient (id,ingredient,valider) VALUES($idIngredient,$prop,0)");
        $ingredient_sql->execute($ingredientRecetteSql, $ingredient_sql);
    }

    $sql = "INSERT INTO recette (id, nom, representation, date_publication, etape, temps_realisation, illustration, methode_cuisson, auteur, categorie, difficulte, ingredient) VALUES ($idRecette,$title,$description,$date,$str_etape,$temps_realisation,$recette_image,$methode_cuisson,$auteur,$type_recette,$difficulte,$ingredient);";
    $insert_sql = $bdd->prepare($sql);
    $insert_sql->execute();


    print("Nous avons reçu votre recette !");

?>