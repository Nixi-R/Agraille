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
            // for($i = 40; $i<47; $i++){
            $reqget = $bdd->prepare("SELECT * FROM recette WHERE id_recette=47");
            $reqget->execute();
            // $reqget->execute(array($i));
            $reqget = $reqget->fetch();
            
                print_r($reqget["nom"]);
                echo "<input name='image_47' type='file' accept='image/*'></input><br>";
            // }
        ?>
        <input type='submit' />
        </form>
        <?php
        // for($i = 40; $i<47; $i++){
        if(isset($_FILES["image_47"])){
            echo "test";
                $fp = fopen($_FILES["image_47"]['tmp_name'], 'rb');
                $req = $bdd->prepare("UPDATE recette SET illustration = ? WHERE id_recette = '47'");
                $req->bindValue(1, $fp,PDO::PARAM_LOB);
                $req -> execute();
        }
        // }
    ?>
</body>
</html>