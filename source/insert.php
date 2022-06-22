<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
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
        ?>
        <form action='./insert.php' method='POST' enctype="multipart/form-data">
        <?php
        for($i=1; $i<2; $i++){
            $nom = $bdd->prepare("SELECT nom FROM recette WHERE id_recette = '$i'");
            $nom -> execute();
            $nom = $nom->fetchAll();
            print_r("<h2>" .$nom[0]['nom'] ."</h2>");
            echo "<input name='image_$i' type='file' accept='image/*'></input>";
        }
        ?>
        <input type='submit' />
        </form>
        <?php
        if(isset($_FILES["image_1"])){
            echo "test";
            for($i=1;$i<2;$i++){
                $fp = fopen($_FILES["image_$i"]['tmp_name'], 'rb');
                $req = $bdd->prepare("UPDATE recette SET illustration = ? WHERE id_recette = '$i'");
                $req->bindValue(1, $fp,PDO::PARAM_LOB);
                $req -> execute();
            }
        }
    ?>
</body>
</html>