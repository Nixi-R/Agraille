<?php 
    session_start();

    try{
        $bdd = new PDO(
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

        if (!(isset($_SESSION['mode'])))
            header('Location: ../');

    $insertRecipe = 'UPDATE recette SET nom = "'.$_GET['nom'].'", representation = "'. $_GET["representation"]. '",
     date = "'.date("Y-m-d H:i:s").'", etape = "'.$_GET["etape"].'", temps_realisation = "'.$_GET["temps_realisation"].'",
     ingredients = "'.$_GET["ingredients"]. '", methode_cuisson = "'.$_GET["methode_cuisson"].'", valider = 1, type = "'.$_GET["type"].'",
      difficulte = "'.$_GET["difficulte"].'" WHERE id = "'.$_GET["id"].'"';

    $insertRecipe = $conn->prepare($insertRecipe);

    $insertRecipe->execute();

    if (strlen($_FILES['illustration']['tmp_name']) > 0)
    {
        $insertRecipe = "UPDATE recette SET illustration = '".fopen($_FILES['photo']['tmp_name'], 'rb')."', 
        mime = '".$_FILES['photo']['type']."'";
        $insertRecipe = $conn->prepare($insertRecipe);
        $insertRecipe->execute();
    }