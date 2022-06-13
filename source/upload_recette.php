<?php 
    session_start();

    try{
        $conn = new PDO(
            'mysql:host=localhost;dbname=agrailledb;charset=utf8',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        }
    catch (Exception $e)
        {
                die('Erreur : ' . $e->getMessage());
        }

    if (!(isset($_POST['nom'])) || !(isset($_POST["representation"])) || !(isset($_POST["etape"])) || !(isset($_POST["temps_realisation"])) || !(isset($_POST["ingredients"])) || !(isset($_POST["methode_cuisson"])) || !(isset($_POST["type"])) || !(isset($_POST["difficulte"])))
        header('Location: ./recette.php?id='. $_POST["id"]. '&err=Il manque des éléments');

    else{
        if (!(isset($_SESSION['mode'])))
        header('Location: ../');

        if (isset($_POST['valider']))
        {    $insertRecipe = 'UPDATE recette SET nom = "'.$_POST['nom'].'", representation = "'. $_POST["representation"]. '",
            date_publication = "'.date("Y-m-d H:i:s").'", etape = "'.$_POST["etape"].'", temps_realisation = "'.$_POST["temps_realisation"].'",
            ingredients = "'.$_POST["ingredients"]. '", methode_cuisson = "'.$_POST["methode_cuisson"].'", valider = 1, type = "'.$_POST["type"].'",
            difficulte = "'.$_POST["difficulte"].'" WHERE id = "'.$_POST["id"].'"';

            $insertRecipe = $conn->prepare($insertRecipe);

            $insertRecipe->execute();

            if (count($_FILES) > 0)
            {
                $insertRecipe = "UPDATE recette SET illustration = '".fopen($_FILES['photo']['tmp_name'], 'rb')."',
                mime = '".$_FILES['photo']['type']."'  WHERE id = '".$_POST['id']."'";
                $insertRecipe = $conn->prepare($insertRecipe);
                $insertRecipe->execute();
            }
        }
        else if (isset($_POST['refuser']))
        {
            $insertRecipe = 'DELETE FROM recette WHERE id = "'.$_POST['id'].'"';
            $insertRecipe = $conn->prepare($insertRecipe);
            $insertRecipe->execute();
        }
    }

    