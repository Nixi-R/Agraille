<?php

if ($_POST['verif_password'] != $_POST['password'])
    header('Location: ./inscription.php?erreur=les mots de passe ne correspondent pas&pseudo='.$_POST['pseudo'].'&email='.$_POST['email'].'&password='.$_POST['password']);

if (iconv_strlen($_POST['password']) < 4 || iconv_strlen($_POST['password']) > 30 || iconv_strlen($_POST['pseudo']) < 4 || iconv_strlen($_POST['pseudo']) > 30 )
    header("Location: ./inscription.php?erreur=erreur de saisie".iconv_strlen($_POST['pseudo'])."p". iconv_strlen($_POST['password']) ."&pseudo=".$_POST['pseudo']."&email=".$_POST['email']."&password=".$_POST['password']);

try{
$conn = new PDO(
    'mysql:host=localhost;dbname=agrailledb;charset=utf8',
    'root',
    '',
    [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}


if (strlen($_FILES['photo']['tmp_name']) > 0){
    $fp = fopen($_FILES['photo']['tmp_name'], 'rb');
    $mime = $_FILES['photo']['type'];
}
else{
    $fp = fopen("../img/Logoutilisateur.png", "rb");
    $mime = "image/png";
}

$recipeStatement = $conn->prepare('SELECT id FROM compte');
$recipeStatement -> execute();
    
$recipeStatement = $recipeStatement -> fetchAll();

$pseudo = strtoupper($_POST['pseudo']);

if (isset($recipeStatement[0]['pseudo']))
    {
        for ($i = 0; $i < count($recipeStatement); $i++)
        {
            if ($pseudo == $recipeStatement[$i]['pseudo'])
            {
                header('Location: ./inscription.php?erreur=pseudo deja éxistant&email='.$_POST['email'].'&password='.$_POST['password']);
            }
            else
            {
                $verify = false;
            }
                
        }
    }

$verify = true;

$recipeStatement = $conn->prepare('SELECT adresse_mail FROM compte');
$recipeStatement -> execute();
    
$recipeStatement = $recipeStatement -> fetchAll();

$pseudo = $_POST['email'];
    
if (isset($recipeStatement[0]['adresse_mail']))
    {
        for ($i = 0; $i < count($recipeStatement); $i++)
        {
            if ($pseudo == $recipeStatement[$i]['adresse_mail'])
            {
                header('Location: ./inscription.php?erreur=adresse mail déjà éxistante&email='.$_POST['email'].'&password='.$_POST['password']);
            }
            else
            {
                $verify = false;
            }
                
        }
    }

$verify = true;

$id = random_int(0, 2147483647);

$recipeStatement = $conn->prepare('SELECT id FROM compte');
$recipeStatement -> execute();

$recipeStatement = $recipeStatement -> fetchAll();

if (isset($recipeStatement[0]['id']))
{
    while($verify)
    {
        $id = random_int(0, 2147483647);
        for ($i = 0; $i < count($recipeStatement); $i++)
        {
            if ($id == $recipeStatement[$i]['id'])
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
}

$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sqlQuery = 'INSERT INTO compte(id, pseudo, adresse_mail, mot_de_passe, photo_de_profil, mime, droit) VALUES (?,?,?,?,?,?,1)';

$insertRecipe = $conn->prepare($sqlQuery);

$insertRecipe -> bindValue(1, $id, PDO::PARAM_STR);
$insertRecipe -> bindValue(2, $pseudo, PDO::PARAM_STR);
$insertRecipe -> bindValue(3, $_POST['email'], PDO::PARAM_STR);
$insertRecipe -> bindValue(4, $hash, PDO::PARAM_STR);
$insertRecipe -> bindValue(5, $fp, PDO::PARAM_LOB);
$insertRecipe -> bindValue(6, $mime, PDO::PARAM_STR);

$insertRecipe->execute();

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../css/certif_compte.css">
        <title>Agraille Inscription</title>
        <link rel="icon" href="../img/icone_agraille.png" sizes="any">
    </head>
    <body>
        <div id="upside">
            <img onclick="location.href='../'" id="agrailleImg" src="../img/logo_agraille.png">
        </div>
        <div id="squareSign">
            <p id="main">INSCRIPTION</p>
            <p onclick="location.href='../'" id="certif">Vous avez fini votre inscription !</br>
            Vous pouvez maintenant vous connecter en cliquant sur ce message</p>
        </div>
    </body>
</html>
