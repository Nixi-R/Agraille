<?php
try{
$conn = new PDO(
    'mysql:host=localhost;dbname=agrailledb;charset=utf8',
    'root',
    '',
    [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1 COLLATE latin1_general_ci",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

$fp = fopen($_FILES['photo']['tmp_name'], 'rb');

if ($_POST['verif_password'] != $_POST['password'])
    header('Location: ./inscription.php?erreur=les mots de passe ne correspondent pas&pseudo='.$_POST['pseudo'].'&email='.$_POST['email'].'&password='.$_POST['password']);

$recipeStatement = $conn->prepare('SELECT id FROM compte');
$recipeStatement -> execute();
    
$recipeStatement = $recipeStatement -> fetchAll();

$pseudo = $_POST['pseudo'];
    
if (isset($recipeStatement[0]['pseudo']))
    {
        for ($i = 0; $i < count($recipeStatement); $i++)
        {
            if ($pseudo == $recipeStatement[$i]['pseudo'])
            {
                header('Location: ./inscription.php?erreur=pseudo deja existant&email='.$_POST['email'].'&password='.$_POST['password']);
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

$sqlQuery = 'INSERT INTO compte(id, pseudo, adresse_mail, mot_de_passe, photo_de_profil, mime, droit) VALUES (?,?,?,?,?,?,1)';

$insertRecipe = $conn->prepare($sqlQuery);

$insertRecipe -> bindValue(1, $id, PDO::PARAM_STR);
$insertRecipe -> bindValue(2, $_POST['pseudo'], PDO::PARAM_STR);
$insertRecipe -> bindValue(3, $_POST['email'], PDO::PARAM_STR);
$insertRecipe -> bindValue(4, $_POST['password'], PDO::PARAM_STR);
$insertRecipe -> bindValue(5, $fp, PDO::PARAM_LOB);
$insertRecipe -> bindValue(6, $_FILES['photo']['type'], PDO::PARAM_STR);

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
