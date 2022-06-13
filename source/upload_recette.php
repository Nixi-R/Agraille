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

    if (strlen($_POST['nom']) < 1 || strlen($_POST["representation"]) < 1 || strlen($_POST["etape"]) < 1 || strlen($_POST["temps_realisation"]) < 1 || strlen($_POST["ingredients"]) < 1 || strlen($_POST["methode_cuisson"]) < 1 || strlen($_POST["type"]) < 1 || strlen($_POST["difficulte"]) < 1)
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

            echo "<!DOCTYPE html>
            <html>
                <head>
                    <link rel=\"stylesheet\" href=\"../css/certif_compte.css\">
                    <title>Agraille Validation</title>
                    <link rel=\"icon\" href=\"../img/icone_agraille.png\" sizes=\"any\">
                </head>
                <body>
                    <div id=\"upside\">
                        <img onclick=\"location.href='../'\" id=\"agrailleImg\" src=\"../img/logo_agraille.png\">
                    </div>
                    <div id=\"squareSign\">
                        <p id=\"main\">".$_POST['nom']."</p>
                        <p onclick=\"location.href='../'\" id=\"certif\">La recette est maintenant validée</br>
                        Vous pouvez retourner à l'index en cliquant sur ce texte</p>
                    </div>
                </body>
            </html>";
        }
        else if (isset($_POST['refuser']))
        {
            $insertRecipe = 'DELETE FROM recette WHERE id = "'.$_POST['id'].'"';
            $insertRecipe = $conn->prepare($insertRecipe);
            $insertRecipe->execute();

            echo "<!DOCTYPE html>
            <html>
                <head>
                    <link rel=\"stylesheet\" href=\"../css/certif_compte.css\">
                    <title>Agraille Validation</title>
                    <link rel=\"icon\" href=\"../img/icone_agraille.png\" sizes=\"any\">
                </head>
                <body>
                    <div id=\"upside\">
                        <img onclick=\"location.href='../'\" id=\"agrailleImg\" src=\"../img/logo_agraille.png\">
                    </div>
                    <div id=\"squareSign\">
                        <p id=\"main\">".$_POST['nom']."</p>
                        <p onclick=\"location.href='../'\" id=\"certif\">La recette a été refusée</br>
                        Vous pouvez retourner à l'index en cliquant sur ce texte</p>
                    </div>
                </body>
            </html>";
        }
    }?>
    
    