<?php
    session_start();
    $bdd = new PDO ('mysql:host=localhost;dbname=agrailledb;charset=utf8','root','', [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    if (isset($_SESSION['idCompte']))
    {  
        $insertP = $bdd->prepare('SELECT photo_de_profil FROM compte WHERE id_compte =' .$_SESSION['idCompte']);
        $insertP -> execute();
        $insertP = $insertP->fetchAll();
    }
    

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

    if($_POST["methode_cuisson"] == "Unité"){
        $methode_cuisson = "";
    }

    $ing_v = 0;
    $ing_nv = 0;

    for($i = 0; isset($_POST["ingredient_$i"]); $i++){
       if($_POST["mesure_$i"] == "Aucune"){
        $_POST["mesure_$i"] = "";

       }

        $ingredient[$i] = $_POST["quantite_$i"]  ." " .$_POST["mesure_$i"] ." de " .$_POST["ingredient_$i"];
        $ing_v++;
    }

    for($o = 1; isset($_POST["ingredient_prop_$o"]); $o++){
        if($_POST["ingredient_prop_mesure_$o"] == "Aucune"){
         $_POST["ingredient_prop_mesure_$o"] = "";
 
        }
        $varchar = $_POST["ingredient_prop_quantite_$o"]  ." " .$_POST["ingredient_prop_mesure_$o"] ." de " .$_POST["ingredient_prop_$o"];
        array_push($ingredient,$varchar);
        // $ingredient[$i] = $_POST["ingredient_prop_quantite_$o"]  ." " .$_POST["ingredient_prop_mesure_$o"] ." de " .$_POST["ingredient_prop_$o"];
        // $i++;
        $ing_nv++;
    }

    $dewa = file_get_contents("../blacklist_ingredient.txt");

    $limit = strlen($dewa) - 1;
     echo $limit;
    for ($n = 1; $n <= $ing_nv; $n++)
    {
        $prevpos = 0;
        $pos = 0;
        $t = 0;

        // while($limit > $prevpos)
        // {
        //     $pos = strpos($dewa, ",", $prevpos +1);

        //     $result = substr($dewa, $prevpos+$t, ($pos - $prevpos)- $t);

        //     if ($_POST["ingredient_prop_$n"] == $result)
        //     {
        //         header("Location: ./redaction_recette.php?erreur=ingredient interdit utilisé&ingredient_nombre=$ing_v");
        //         exit();
        //     }

        //     $prevpos = $pos;
        //     $t = 1;
        // }
    }

/////



    for($i = 1; isset($_POST["ingredient_prop_$i"]); $i++){
        $ingredient_prop[$i] = $_POST["ingredient_prop_$i"];
        $idIngredient = randomize("id_ingredient", "ingredient");
        $ingredient_sql = $bdd->prepare("INSERT INTO ingredient (id_ingredient,ingredient,valider) VALUES($idIngredient,?,0)");
        $ingredient_sql->execute(array($ingredient_prop[$i]));

        $tempo = $_POST["ingredient_prop_quantite_$i"] ." " .$_POST["ingredient_prop_mesure_$i"] ."de" .$ingredient_prop[$i];

        array_push($ingredient, $tempo);
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
    
    $sql = "INSERT INTO recette (id_recette, nom,ingredient,representation, date_publication, etape, temps_realisation, illustration, methode_cuisson, categorie, difficulte, valider) VALUES ($idRecette,'$title','$ingredient','$description',NOW(),'$str_etape','$temps_realisation',?,'$methode_cuisson','$type_recette','$difficulte', 0);";
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

    $total_ing = $ing_nv + $ing_v;

    for ($y = 0; $y < $total_ing; $y++) {
        for ($x = 0; $x < count($id_ingredient); $x++) {
            if ( $y < $ing_v  && $id_ingredient[$x][1] == $_POST["ingredient_$y"]) {
                $id_recette_as_ingredient = randomize("id_recette_as_ingredient", "recette_as_ingredient");
                $ingredient_id = $id_ingredient[$x][0];            
                $query_ingredient = "INSERT INTO recette_as_ingredient (id_recette_as_ingredient, id_ingredient, id_recette) VALUES ($id_recette_as_ingredient, $ingredient_id, $idRecette);";
                $insert_ingredient = $bdd->prepare($query_ingredient);
                $insert_ingredient->execute();
            }
            else if ($y >= $ing_v && $id_ingredient[$x][1] == $ingredient_prop[$y-$ing_v+1])
            {
                $id_recette_as_ingredient = randomize("id_recette_as_ingredient", "recette_as_ingredient");
                $ingredient_id = $id_ingredient[$x][0];            
                $query_ingredient = "INSERT INTO recette_as_ingredient (id_recette_as_ingredient, id_ingredient, id_recette) VALUES ($id_recette_as_ingredient, $ingredient_id, $idRecette);";
                $insert_ingredient = $bdd->prepare($query_ingredient);
                $insert_ingredient->execute();
            }
        }
    }

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/envoi_recette.css">
    <link rel="icon" href="./img/icone_agraille2.png" sizes="any">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <header>
    <nav>
            <div class="nav-burger">
                <ul class="nav-menu">
                    <li class="nav-item">
                       <a href="../index.php"><img src="../img/icone_agraille.png"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index?categorie=cocktail" >Cocktails</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index?categorie=plats">Plats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index?categorie=dessert">Desserts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./a_propos">A propos</a>
                    </li>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
            <div class="nav-container">
                <div class="nav-logo">
                <a href="../index.php"><img src="../img/logo_agraille.png"></a>
                </div>
                <div class="search-bar">
                    <form action="./page_filtrage" method="post">
                        <input class="search-input" type="text" name="nom" placeholder="Entrer un plat...">
                            <i class="search-input-icon fa fa-search"></i>
                        </input>
                    </form>
                </div>
                <a class="img_filtre" href="./page_filtrage.php"><img id="img_filtre" src="../img/filtre.png"/></a>
                <div class="d-grid gap-2 d-md-block">
                    <?php
                        if(isset($_SESSION['idCompte'])){        
                            if (preg_match('/JFIF/i',substr($insertP[0]['photo_de_profil'], 0, 10)))
                                echo '<img id="img_profil_pics" src="data:image/jpg;base64,' . base64_encode($insertP[0]['photo_de_profil']) . '">';
                            else if (preg_match('/GIF/i',substr($insertP[0]['photo_de_profil'], 0, 3)))
                                echo '<img id="img_profil_pics" src="data:image/gif;base64,' . base64_encode($insertP[0]['photo_de_profil']) . '">';
                            else if (preg_match('/PNG/i',substr($insertP[0]['photo_de_profil'], 1, 3)))
                                echo '<img id="img_profil_pics" src="data:image/png;base64,' . base64_encode($insertP[0]['photo_de_profil']) . '">';
                           echo "<div class='container_arrow'>
                                    <span class='arrow'></span>
                                    <span class='arrow'></span>
                                 </div>";
                        }else{
                            echo"<a href='./connexion.php'><button type='button' class='btn btn-primary'>Se connecter</button></a>
                            <a href='./inscription.php'><button type='button' class='btn btn-primary'>S'inscrire</button></a>";
                        }
                     ?>
                </div>
            </div>
        </nav>
    </header>
    <div class="profil_menu">
        <ul>
            <li><?php echo "<b>Bonjour ".$_SESSION["pseudo"]."!</b>"; ?></li>
            <li><a href="./profil.php">Voir profil</a></li>
            <li><a href="./confirmation_ingredient.php">Créer une recette</a></li>
            <?php
            if (isset($_SESSION['mode']))
                echo "<li><a href='../index?mode=change'>Changement de mode</a></li>";
            ?>
            <li><a onclick="location.href='./disconnect'" href="#">Se déconnecter</a></li>
        </ul>
    </div>
    <main>
        <h5>Nous avons reçu votre recette !</h5>
        <a href='../index'>retour à l'acceuil</a>
        <?php echo $limit; ?>
    </main> 
    <script src="../js/scriptIndex.js"></script>
</body>
</html>
<?php


function randomize (string $champs, string $table)
{

    $bdd = new PDO ('mysql:host=localhost;dbname=agrailledb;charset=utf8','root','', [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $id = random_int(0, 2147483647);

    $recipeStatement = $bdd->prepare('SELECT '. $champs .' FROM '. $table);
    $recipeStatement -> execute();

    $recipeStatement = $recipeStatement -> fetchAll();

    $verify =true;

    if (empty($recipeStatement)){
        $verify = false;
    }

    while($verify)
    {
        $id = random_int(0, 2147483647);
        for ($i = 0; $i < count($recipeStatement); $i++)
        {
            if ($id == $recipeStatement[$i][$champs])
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
