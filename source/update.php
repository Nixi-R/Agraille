<?php 
    session_start();
    $bdd = new PDO ('mysql:host=localhost;dbname=agrailledb;charset=utf8','root','', [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1 COLLATE latin1_general_ci",PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $id = $_SESSION["idCompte"];

    if(isset($_REQUEST['email_form'])){
        $email = $_REQUEST['email_form'];
        if(empty($email)){
            $req = $bdd->prepare("SELECT adresse_mail FROM compte WHERE id=$id");
        }else{
            $req = $bdd->prepare("UPDATE compte SET adresse_mail = '$email' WHERE id=$id");
            $_SESSION["adresse_mail"] = $_REQUEST["email_form"];
        }
            $req->execute();
    }

    if(isset($_REQUEST['pseudo_form'])){
        $pseudo = $_REQUEST['pseudo_form'];
        if(empty($pseudo)){
            $req = $bdd->prepare("SELECT pseudo FROM compte WHERE id=$id");
        }else{
            $req = $bdd->prepare("UPDATE compte SET pseudo='$pseudo' WHERE id=$id");
            $_SESSION["pseudo"] = $pseudo;
        }
        $req->execute();
    }
                            
    if(isset($_REQUEST['password_form'])){
        $password = $_REQUEST['password_form'];
            if(empty($password)){
                $req = $bdd->prepare("SELECT mot_de_passe FROM compte WHERE id=$id");
            }else{
                $req = $bdd->prepare("UPDATE compte SET mot_de_passe = '$password' WHERE id=$id");
            }
            $req->execute();
    }

     if(isset($_FILES['profil_pic'])){
        $fp = fopen($_FILES['profil_pic']['tmp_name'], 'rb');
        $pic = "UPDATE compte SET photo_de_profil = ?, mime = ? WHERE id=$id"; 
        $req = $bdd->prepare($pic);
        $req -> bindValue(1, $fp, PDO::PARAM_LOB);
        $req -> bindValue(2, $_FILES['profil_pic']['type'], PDO::PARAM_STR);
        $req->execute();
        }

    header('Location: profil.php');
?>