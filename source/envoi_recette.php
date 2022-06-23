<?php
    session_start();
    $bdd = new PDO ('mysql:host=localhost;dbname=agrailledb;charset=utf8','root','', [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $title = $_POST["title"];
    $description = $_POST["description"];
    $methode_cuisson = $_POST["methode_cuisson"];
    $etape = array();
    $temps_realisation = $_POST["temps_realisation"];
    $difficulte = $_POST["difficulte"];
    $type_recette = $_POST["type_recette"];
    $recette_image = fopen($_FILES["recette_image"]['tmp_name'], 'rb');
    $img_mime = $_FILES['recette_image']['type'];

    if ($img_mime != "image/png" && $img_mime != "image/jpg" && $img_mime != "image/jpeg" && $img_mime != "image/gif")
    {
        header("Location: ./redaction_recette.php");
        exit();
    }
    
    $date = date("Y-m-d H:i:s");
    $idRecette = random_int(0, 2147483647);
    $ingredient = array();
    $ingredient_prop = array();

    if($_POST["methode_cuisson"] == "Aucune"){
        $methode_cuisson = "";
    }

    for($i = 0; isset($_POST["ingredient_$i"]); $i++){
        $ingredient[$i] = $_POST["quantite_$i"]  ." " .$_POST["mesure_$i"] ." de " .$_POST["ingredient_$i"];
    }


///// 
    for($i = 1; isset($_POST["ingredient_prop_$i"]); $i++){
        $ingredient_prop[$i] = $_POST["ingredient_prop_$i"];
        $idIngredient = random_int(0, 2147483647);
        $ingredient_sql = $bdd->prepare("INSERT INTO ingredient (id_ingredient,ingredient,valider) VALUES($idIngredient,$ingredient_prop,0)");
        $ingredient_sql->execute();

        $tempo = $_POST["ingredient_prop_quantite_$i"] ." " .$_POST["ingredient_prop_mesure_$i"] ."de" .$ingredient_prop;

        $ingredient = array_push($tempo);
    }

    $ingredient = implode(". ", $ingredient);

////
    for ($i = 1; isset($_POST["step_$i"]); $i++) {
        array_push($etape, $_POST["step_$i"]);
    }

    $str_etape = implode(". ", $etape);
    $temps_realisation = "00:".$temps_realisation.":00";
    $sql = "INSERT INTO recette (id_recette, nom, representation, date_publication, etape, temps_realisation, illustration, methode_cuisson, categorie, difficulte, valider) VALUES ($idRecette,'$title','$description','$date','$str_etape','$temps_realisation',?,'$methode_cuisson','$type_recette','$difficulte', 0);";
    $insert_sql = $bdd->prepare($sql);
    $insert_sql -> bindValue(1, $recette_image, PDO::PARAM_LOB);
    $insert_sql->execute();

    $idCompte = $_SESSION["idCompte"];
    $liaison = "INSERT INTO compte_as_recette (id_compte_as_recette, id_recette, id_compte) VALUES ($idRecette, $idCompte, $idRecette);";
    $insert_liaison = $bdd->prepare($liaison);
    $insert_liaison->execute();

    print("Nous avons reçu votre recette !");
    echo "<a href='../index'>retour à l'acceuil</a>"

?>