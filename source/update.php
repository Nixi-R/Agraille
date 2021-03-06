<?php 
    session_start();
    $bdd = new PDO ('mysql:host=localhost;dbname=agrailledb;charset=utf8','root','', [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $id = $_SESSION["idCompte"];

    if(isset($_REQUEST['email_form'])){
        $email = $_REQUEST['email_form'];
        if(!empty($email)){
            if (!(preg_match("/@/i", $email)) || !(preg_match("/\./i", stristr($email, "@")))){
                header("Location: ./profil.php?err=format mail incorrect");
                exit();
            }else{
                $req = $bdd->prepare("UPDATE compte SET adresse_mail = '$email' WHERE id_compte=$id");
                $_SESSION["adresse_mail"] = $_REQUEST["email_form"];
                $req->execute();
            }
        }             
    }

    if(isset($_REQUEST['pseudo_form'])){
        $pseudo = $_REQUEST['pseudo_form'];
        if(!empty($pseudo)){
            $req = $bdd->prepare("SELECT pseudo FROM compte WHERE id_compte=$id");
            $req = $bdd->prepare("UPDATE compte SET pseudo='$pseudo' WHERE id_compte=$id");
            $_SESSION["pseudo"] = $pseudo;
            $req->execute();
        }
    }
                            
    if(isset($_REQUEST['password_form'])){
            if(!empty($_REQUEST['password_form'])){
                $hash = password_hash($_REQUEST['password_form'], PASSWORD_DEFAULT);
                $req = $bdd->prepare("UPDATE comp:te SET mot_de_passe = ? WHERE id_compte=$id");
                $req -> bindValue(1,$hash, PDO::PARAM_STR);
                $req->execute();
            }
    }

     if(isset($_FILES['profil_pic']['tmp_name'])){
    {
        $img_mime = $_FILES['profil_pic']['type'];
        if ($img_mime != "image/png" && $img_mime != "image/jpg" && $img_mime != "image/jpeg" && $img_mime != "image/gif")
        {
            header("Location: ./profil.php");
            exit();
        }
    }
        $fp = fopen($_FILES['profil_pic']['tmp_name'], 'rb');
        $pic = "UPDATE compte SET photo_de_profil = ? WHERE id_compte=$id"; 
        $req = $bdd->prepare($pic);
        $req -> bindValue(1, $fp, PDO::PARAM_LOB);
        $req->execute();
        }

    header('Location: profil.php');
?>