<?php
    $bdd = new PDO(
        'mysql:host=localhost;dbname=messagerie;charset=utf8;',
        'root',
        ''
    );
    
    if(isset($_POST['valider'])) {
        if((isset($_POST['message'])) AND (!empty($_POST['message']))) {
            $id = random_int(0, 2147483647);
            $pseudo = htmlspecialchars($_SESSION['pseudo']);
            $message = nl2br(htmlspecialchars($_POST['message']));
            /*$ins = $bdd->prepare('INSERT INTO commentaire (id_commentaire, texte_commentaire, date_commentaire, id_compte_as_recette,id_compte) VALUES (?,?,NOW(),?,?)');
            $ins->execute(array($id, $commentaire,$getid,$_SESSION["idCompte"]));
                
            $req = $bdd->prepare('INSERT INTO compte_as_recette (id_compte_as_recette,id_compte,id_recette) VALUES(?,?,?)');
            $req->execute(array($id,$_SESSION["idCompte"],$getid));*/

            $insererMessage = $bdd->prepare("INSERT INTO messagerie(id_messagerie, texte_messagerie, date_messagerie, id_compte_as_recette, id_compte) VALUES (?,?,NOW(),?,?)"); 
            $insererMessage->execute(array($id, $message, $_SESSION["idCompte"]));
        }else {
            echo "Tous les champs doivent être complétés";
        }
    }

    /*$req_com = $bdd->prepare("SELECT pseudo FROM compte INNER JOIN messagerie ON compte.id_compte = messagerie.id_compte WHERE  messagerie.id_messagerie = ?");
    $req_com->bindValue(1, $id['id_messagerie']);
    $req_com->execute();*/
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/messagerie.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="../img/icone_agraille2.png" sizes="any">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>Agraille|Chat</title>
</head>
<body>
    <header>
        <nav>
            <div class="nav-burger">
                <ul class="nav-menu">
                    <li class="nav-item">
                       <a href="../index.php"><img src="../img/icone_agraille.png"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index?categorie=cocktail">Cocktails</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index?categorie=plats">Plats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index?categorie=dessert">Desserts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./a_propos">A propos</a>
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
                    <form action="#" method="post">
                        <input class="search-input" type="text" name="nom" placeholder="Entrer un plat...">
                            <i class="search-input-icon fa fa-search"></i>
                        </input>
                    </form>
                </div>
                <a class="img_filtre" href="./page_filtrage.php"><img src="../img/filtre.png"/></a>
                <div class="d-grid gap-2 d-md-block">
                    <?php
                        if(isset($_SESSION['idCompte'])){  
                            if (preg_match('/GIF/i',substr($insertP[0][0], 0, 3)))
                                echo '<img id="img_profil_pics" src="data:image/gif;base64,' . base64_encode($insertP[0][0]) . '"';
                            else if (preg_match('/PNG/i',substr($insertP[0][0], 1, 3)))
                                echo '<img id="img_profil_pics" src="data:image/png;base64,' . base64_encode($insertP[0][0]) . '"';
                            else
                                echo '<img id="img_profil_pics" src="data:image/jpg;base64,' . base64_encode($insertP[0][0]) . '"';
                            echo "<div class='container_arrow'>
                                <span class='arrow'></span>
                                <span class='arrow'></span>
                             </div>";
                        }else{
                            echo"<a href='./connexion.php'><button type='button' class='btn btn-primary'>Se connecter</button></a>
                            <a href='./inscription.php'><button type='button' class='btn btn-primary'>S'inscrire</button></a>";
                        }
                     ?>
                </div>
            </div>
        </nav>
    </header>
    <div class="profil_menu">
        <ul>
            <li><?php echo $_SESSION["pseudo"]; ?></li>
            <li><a href="./profil.php">Voir profil</a></li>
            <li><a href="./confirmation_ingredient.php">Créer une recette</a></li>
            <?php
            if (isset($_SESSION['mode']))
                echo "<li><a href='./messagerie?mode=change'>Changement de mode</a></li>";
            ?>
            <li><a onclick="location.href='./disconnect?'" href="#">Se déconnecter</a></li>
        </ul>
    </div>
    <main>
        <div id="main">
            <div class="margTitre"><h2>CHAT EN LIGNE</h2></div><br/>
            <form method="POST" action="" align="center">
                <h5>Message</h5><textarea name="message"></textarea>
                <br/>
                <input type="submit" name="valider">
                <br/><br/>
            </form>
            <section id="messages"></section>

            <script>
                setInterval('charger_messages()', 500); //execute toutes les 500ms
                function charger_messages(){
                    $('#messages').load('charger_messages.php');
                }
            </script>
            <script src="../js/scriptIndex.js"></script>
        </div>
    </main>
</body>
</html>