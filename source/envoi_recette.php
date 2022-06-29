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
    
    $idRecette = random_int(0, 2147483647);
    $ingredient = array();
    $ingredient_prop = array();

    if($_POST["methode_cuisson"] == "Aucune"){
        $methode_cuisson = "";
    }


    for($i = 0; isset($_POST["ingredient_$i"]); $i++){
       if($_POST["mesure_$i"] == "Aucune"){
        $_POST["mesure_$i"] = "";

       }

        $ingredient[$i] = $_POST["quantite_$i"]  ." " .$_POST["mesure_$i"] ." de " .$_POST["ingredient_$i"];
    }


/////



    for($i = 1; isset($_POST["ingredient_prop_$i"]); $i++){
        $ingredient_prop[$i] = $_POST["ingredient_prop_$i"];
        $idIngredient = randomize("id_ingredient", "ingredient");
        $ingredient_sql = $bdd->prepare("INSERT INTO ingredient (id_ingredient,ingredient,valider) VALUES($idIngredient,?,0)");
        $ingredient_sql->execute(array($ingredient_prop[$i]));

        $tempo = $_POST["ingredient_prop_quantite_$i"] ." " .$_POST["ingredient_prop_mesure_$i"] ."de" .$ingredient_prop[$i];

        $ingredient = array_push($tempo);
    }

    $ingredient = implode(". ", $ingredient);

////
    for ($i = 1; isset($_POST["step_$i"]); $i++) {
        array_push($etape, $_POST["step_$i"]);
    }

    $str_etape = implode(". ", $etape);

    if ($temps_realisation >= 60)
        $temps_realisation = "0". floor($temps_realisation / 60).":". ($temps_realisation % 60) . ":00";
    else
        $temps_realisation = "00:".$temps_realisation.":00";
    
    $sql = "INSERT INTO recette (id_recette, nom, representation, date_publication, etape, temps_realisation, illustration, methode_cuisson, categorie, difficulte, valider,ingredient) VALUES ($idRecette,'$title','$description',NOW(),'$str_etape','$temps_realisation',?,'$methode_cuisson','$type_recette','$difficulte', 0,'$ingredient');";
    $insert_sql = $bdd->prepare($sql);
    $insert_sql -> bindValue(1, $recette_image, PDO::PARAM_LOB);
    $insert_sql->execute();

    $idCompte = $_SESSION["idCompte"];
    $link = "INSERT INTO compte_as_recette (id_compte_as_recette, id_recette, id_compte) VALUES ($idRecette, $idRecette, $idCompte);";
    $insert_link = $bdd->prepare($link);
    $insert_link->execute();

    $id_ingredient = $bdd->query('SELECT * FROM ingredient;');
    $id_ingredient -> execute();
    $id_ingredient = $id_ingredient -> fetchAll();

    for ($y = 0; isset($_POST["ingredient_$y"]); $y++) {
        for ($x = 0; $x < count($id_ingredient); $x++) {
            if ($id_ingredient[$x][1] == $_POST["ingredient_$y"]) {
                $id_recette_as_ingredient = randomize("id_recette_as_ingredient", "recette_as_ingredient");
                $ingredient_id = $id_ingredient[$x][0];             //tu fais un = donc ingredient_id est égal à 1, du coup les id des ingredients suivant seront tous = 1
                $query_ingredient = "INSERT INTO recette_as_ingredient (id_recette_as_ingredient, id_ingredient, id_recette) VALUES ($id_recette_as_ingredient, $ingredient_id, $idRecette);";
                $insert_ingredient = $bdd->prepare($query_ingredient);
                $insert_ingredient->execute();
            }
        }
    }

    print("Nous avons reçu votre recette !");
    echo "<a href='../index'>retour à l'accueil</a>"


function randomize (string $champs, string $table)
{
    $id = random_int(0, 2147483647);

    $recipeStatement = $conn->prepare('SELECT '. $champs .' FROM '. $table);
    $recipeStatement -> execute();

    $recipeStatement = $recipeStatement -> fetchAll();

    while($verify)
    {
        $id = random_int(0, 2147483647);
        for ($i = 0; $i < count($recipeStatement); $i++)
        {
            if ($id == $recipeStatement[$i]['id_recette'])
            {
                $verify = true;
                break;
            }
            else
            {
                    $verify = false;
            }
                
        }
    }
    return $id;
}

?>