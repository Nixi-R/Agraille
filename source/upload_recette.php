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
    
    if (!(isset($_SESSION['mode'])))
    {
        header('Location: ../');
        exit();
    }
    
    if (isset($_FILES['photo']['type']) && !empty($_FILES['photo']['type']))
    {
        $img_mime = $_FILES['photo']['type'];
        if ($img_mime != "image/png" && $img_mime != "image/jpg" && $img_mime != "image/jpeg" && $img_mime != "image/gif")
        {
            header("Location: ./recette.php?id=".$_POST['id']);
            exit();
        }
    }

    if (isset($_POST['valider']))
    {
        if (strlen($_POST['nom']) < 1 || strlen($_POST["representation"]) < 1 || strlen($_POST["etape"]) < 1 || strlen($_POST["temps_realisation"]) < 1 || strlen($_POST["ingredients_0"]) < 1 || strlen($_POST["categorie"]) < 1 || strlen($_POST["difficulte"]) < 1 || !(isset($_FILES['photo']['tmp_name'])) || strlen($_FILES['photo']['tmp_name']) < 1)
        {
            if($_POST['categorie'] == 'plat' || $_POST['categorie'] == 'dessert')
            {
                header('Location: ./recette.php?id='. $_POST["id"]. '&err=Il manque des éléments');
                exit();
            }

        }

        $_POST['ingredients'] = "";

        for ($i = 0; isset($_POST["ingredients_$i"]); $i++)
        {
            $_POST["ingredients"] .= $_POST["ingredients_$i"];
        }
        $date = date("Y-m-d H:i:s");
        $insertRecipe = 'UPDATE recette SET nom = "'.$_POST['nom'].'", representation = "'. $_POST["representation"]. '",
        date_publication = "'.$date.'", etape = "'.$_POST["etape"].'", temps_realisation = "'.$_POST["temps_realisation"].'",
        ingredient = "'.$_POST["ingredients"]. '", methode_cuisson = "'.$_POST["methode_cuisson"].'", valider = 1, categorie = "'.$_POST["categorie"].'",
        difficulte = "'.$_POST["difficulte"].'" WHERE id_recette = "'.$_POST["id"].'"';

        $insertRecipe = $conn->prepare($insertRecipe);
    
        $insertRecipe->execute();
    
        if (strlen($_FILES['photo']['tmp_name']) > 0)
        {
            $bin = fopen($_FILES['photo']['tmp_name'], 'rb');
            $insertRecipe = "UPDATE recette SET illustration = ?
            WHERE id_recette = '".$_POST['id']."'";
            $insertRecipe = $conn->prepare($insertRecipe);
            $insertRecipe -> bindValue(1, $bin, PDO::PARAM_LOB);
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
        $insertRecipe = 'DELETE FROM recette WHERE id_recette = "'.$_POST['id'].'"';
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
    ?>