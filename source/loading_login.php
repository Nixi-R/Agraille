<?php
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

    
    $recipeS = $conn->prepare('SELECT pseudo,adresse_mail FROM compte');
    $recipeS -> execute();
    $recipeS = $recipeS -> fetchAll();

    for ($i = 0; $i < count($recipeS); $i++)
    {
        if ($_POST['pseudo'] == $recipeS[$i]['pseudo'])
            $exist = true;
        else if($_POST['pseudo'] == $recipeS[$i]['adresse_mail'])
            $exist = false;
    }

    if(isset($exist))
    {
        if ($exist == true)
        {
            $recipeS = $conn->query('SELECT mot_de_passe FROM compte WHERE pseudo = \''.$_POST['pseudo']. '\'');
            $recipeS -> execute();
            $recipeS = $recipeS -> fetch();
            
            if($_POST['password'] == $recipeS['mot_de_passe'])
            {
                $recipeS = $conn->query('SELECT * FROM compte WHERE pseudo = \''.$_POST['pseudo']. '\'');
                $recipeS -> execute();
                $recipeS = $recipeS -> fetchAll();
        
                session_start();
                $_SESSION['idCompte'] = $recipeS[0]['id'];
                $_SESSION['pseudo'] = $recipeS[0]['pseudo'];
                $_SESSION['adresse_mail'] = $recipeS[0]['adresse_mail'];
                $_SESSION['mot_de_passe'] = $recipeS[0]['mot_de_passe'];
                $_SESSION['droit'] = $recipeS[0]['droit'];


                header('Location: ../');
            }
            else
            {
                header('Location: ./connexion.php?erreur=mot de passe incorrect');
            }
        }
        else
        {
            $recipeS = $conn->query('SELECT mot_de_passe FROM compte WHERE adresse_mail = \''.$_POST['pseudo']. '\'');
            $recipeS -> execute();
            $recipeS = $recipeS -> fetch();
            
            if($_POST['password'] == $recipeS['mot_de_passe'])
            {
                header('Location: ../');
            }
            else
            {
                header('Location: ./connexion.php?erreur=mot de passe incorrect');
            }
        }
    }
    else
    {
        header('Location: ./connexion.php?erreur=Pseudo ou email inconnu');
    }


?>

<!DOCTYPE html>
    <head>
        <link rel="stylesheet" href="../css/loading_login.css">
        <title>Chargement...</title>
        <link rel="icon" href="../img/icone_agraille.png" sizes="any">
    </head>
    <body>
        <div id="squareLog">
            <p>Chargement</p>
            <div id="load"></div>
        </div>
    </body>
</html>