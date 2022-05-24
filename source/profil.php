<?php
session_start();
if(!(isset($_SESSION['idCompte'])))
    header('Location: ./connexion');
 
$bdd = new PDO ('mysql:host=localhost;dbname=agrailledb;charset=utf8','root','', [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1 COLLATE latin1_general_ci",
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);



$insertP = $bdd->prepare('SELECT photo_de_profil, mime FROM compte WHERE id ='. $_SESSION['idCompte']);
$insertP -> execute();
$insertP = $insertP->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/icone_agraille.png" sizes="any">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/profil.css">
    <title>Votre profil</title>
</head>
<body>
<header>
        <nav>
            <div class="nav-burger">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="../index.php"><img src="../img/icone_agraille2.png"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" >Aperitifs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Entrees</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Plats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Desserts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">A propos</a>
                    </li>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="../index.php"><img src="../img/logo_agraille.png"></a>
                </div>
                <div class="search-bar">
                    <form action="#" >
                        <input class="search-input" type="text" name="filtrage" placeholder="Entrer un plat ou un ingrédient...">
                            <i class="search-input-icon fa fa-search"></i>
                        </input>
                    </form>
                </div>
                <div class="d-grid gap-2 d-md-block">
                    <?php
                        if(isset($_SESSION['idCompte'])){
                           echo "<img id='img_profil_pics' src='data:". $insertP[0]['mime'] .";base64," . base64_encode($insertP[0]['photo_de_profil']) . "' alt='photo de profil'>";
                           echo "<div class='container_arrow'>
                                    <span class='arrow'></span>
                                    <span class='arrow'></span>
                                 </div>";
                        }else{
                            echo"<a href='./source/connexion.php'><button type='button' class='btn btn-primary'>Se connecter</button></a>
                            <a href='./source/inscription.php'><button type='button' class='btn btn-primary'>S'inscrire</button></a>";
                        }
                     ?>
                </div>
            </div>
        </nav>
    </header>
    <div class="profil_menu">
        <ul>
            <li><?php echo $_SESSION["pseudo"] ?></li>
            <li><a href="profil.php">Voir profil</a></li>
            <li><a href="#">Créer une recette</a></li>
            <li><a onclick="location.href='./source/disconnect'" href="#">Se déconnecter</a></li>
        </ul>
    </div>
    <main>
        <div id="main">
            <h1>Profil</h1>
            <form action="profil.php" enctype="multipart/form-data" method="POST">
                <h2>Photo de profil</h2>
                <div id="container_profil">
                        <div id="profil_pic">
                            <img id="img_profil_pics" src="<?php echo 'data:'. $insertP[0]['mime'] .';base64,' . base64_encode($insertP[0]['photo_de_profil']) . '"';?>" alt="photo de profil">
                        </div>
                        <div id="profil_pic_button">
                            <input id="file_button" type="file" name="profil_pic" accept="image/*">
                        </div>
                </div>
                <h2>Informations</h2>
                <div id="wrapper_information">
                    <div id="pseudo_container">
                        <label>Pseudo : </label>
                        <input id="pseudo_input" type="text" name="pseudo_form" minlength=4 maxlength=30 placeholder="<?php echo $_SESSION['pseudo'] ?>" readonly>
                        <a  id="pseudo_button" onclick="javascript:document.getElementById('pseudo_input').removeAttribute('readonly');">Modifier pseudo</a onclick="javascript:document.getElementById('password_input').style.opacity='1';">
                    </div>
                    <div id="mail_container">
                        <label>Adresse e-mail : </label>
                        <input id="email_input" type="email" name="email_form" placeholder="<?php echo $_SESSION['adresse_mail'] ?>" readonly>
                        <a id="email_button" onclick="javascript:document.getElementById('email_input').removeAttribute('readonly');" >Modifier e-mail</a onclick="javascript:document.getElementById('password_input').style.opacity='1';">
                    </div>
                    <div id="password_container">
                        <label>Mot de passe : </label>
                        <input id="password_input" type="password" name="password_form" readonly>
                        <a id="password_button"  onclick="javascript:document.getElementById('password_input').removeAttribute('readonly');">Modifier mot de passe</a>
                    </div>
                    <div id="send_form_button">
                        <input type="submit" name="send" value="Envoyer">
                    </div>
                 </div>
                 <form method="POST" action="profil.php" name="form">
                     <div id="uninscription_button">
                        <input onclick="location.href='./source/disconnect'" type="submit" name="uninscription" value="Se désinscrire">
                    </div>
                </form>
                    <?php 
                    $id = $_SESSION["idCompte"];
                    if(isset($_REQUEST["uninscription"])){
                            echo "ça fonctionne";
                            $req = $bdd->prepare("DELETE FROM compte WHERE id=$id" );
                            $req->execute();
                            $req = $req-> fetchAll();
                            }

                            if(isset($_REQUEST['email_form'])){
                                $email = $_REQUEST['email_form'];
                                if(empty($email)){
                                    $req = $bdd->prepare("SELECT adresse_mail FROM compte WHERE id=$id");
                                }else{
                                    $req = $bdd->prepare("UPDATE compte SET adresse_mail = '$email' WHERE id=$id");
                                }
                                    $req->execute();
                                    $req = $req-> fetchAll();
                            }

                            if(isset($_REQUEST['pseudo_form'])){
                                $pseudo = $_REQUEST['pseudo_form'];
                                if(empty($pseudo)){
                                    $req = $bdd->prepare("SELECT pseudo FROM compte WHERE id=$id");
                                }else{
                                    $req = $bdd->prepare("UPDATE compte SET pseudo='$pseudo' WHERE id=$id");
                                }
                                $req->execute();
                                $req = $req-> fetchAll();
                                
                            }
                            
                            if(isset($_REQUEST['password_form'])){
                                $password = $_REQUEST['password_form'];
                                if(empty($password)){
                                    $req = $bdd->prepare("SELECT mot_de_passe FROM compte WHERE id=$id");
                                }else{
                                    $req = $bdd->prepare("UPDATE compte SET mot_de_passe = '$password' WHERE id=$id");
                                }
                                $req->execute();
                                $req = $req->fetchAll();
                            }

                            if(isset($_FILES['profil_pic'])){

                                $fp = fopen($_FILES['profil_pic']['tmp_name'], 'rb');

                                $pic = "UPDATE compte SET photo_de_profil = ?, mime = ? WHERE id=$id"; 
                                $req = $bdd->prepare($pic);
                                $req -> bindValue(1, $fp, PDO::PARAM_LOB);
                                $req -> bindValue(2, $_FILES['profil_pic']['type'], PDO::PARAM_STR);
                                $req->execute();
                            }
                    ?>
                </div>
            </form>
        </div>
    </main>
    <script>
            document.getElementById('file_button').onchange = function (e){
                var file = e.target.files[0];

                var reader = new FileReader();
                reader.onloadend = function(){
                    document.getElementById('profil_pic').style.backgroundImage = "url(" + reader.result + ")";
                    document.getElementById('profil_pic').style.backgroundSize = "cover";
                    document.getElementById('img_profil_pics').setAttributes('style', 'visibility:hidden');;
                }
                if(file){
                    reader.readAsDataURL(file);
                }
            }


            // function confirmDelAccount() {
            //     if(confirm("Voulez-vous supprimer votre compte ?")){
            //        form .submit();
            //     }
            // }
        </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="../js/scriptIndex.js"></script>
</body>