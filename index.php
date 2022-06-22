<?php
session_start();

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

    if (isset($_SESSION['idCompte']))
    {  
        $insertP = $bdd->prepare('SELECT photo_de_profil FROM compte WHERE id_compte ='. $_SESSION['idCompte'] );
        $insertP -> execute();
        $insertP = $insertP->fetchAll();
    }else{
        echo"ya rien";
    }

    if (isset($_SESSION['mode']) && isset($_GET['mode']))
        if ($_SESSION['mode'] == 1)
            $_SESSION['mode'] = 0;
        else
            $_SESSION['mode'] = 1;

    if (isset($_SESSION['mode']) && $_SESSION['mode'] == 1){
        if(isset($_GET['categorie']) AND !empty($_GET['categorie'])){
            $getcategorie = htmlspecialchars($_GET['categorie']);
            $recette = $bdd->prepare('SELECT * FROM recette WHERE (categorie = ?) AND (valider = 0)');
            $recette->execute(array($getcategorie));
        }else{
            $recette = $bdd->prepare('SELECT * FROM recette WHERE valider = 0 ORDER BY id DESC');
            $recette->execute();
        }
    }else{ 
        if(isset($_GET['categorie']) AND !empty($_GET['categorie'])){
            $getcategorie = htmlspecialchars($_GET['categorie']);
            $recette = $bdd->prepare('SELECT * FROM recette WHERE (categorie= ?) AND (valider = 1)');
            $recette->execute(array($getcategorie));
        }else{
            $recette = $bdd->prepare('SELECT * FROM recette WHERE valider = 1 ORDER BY id_recette DESC');
            $recette->execute();
        }
    }


    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="icon" href="./img/icone_agraille2.png" sizes="any">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Agraille|<?php if (isset($_SESSION['mode'])  && $_SESSION['mode'] == 1) echo "Validation"; else echo "Accueil";?></title>
</head>
<body>
    <header>
        <nav>
            <div class="nav-burger">
                <ul class="nav-menu">
                    <li class="nav-item">
                       <a href="./index.php"><img src="img/icone_agraille.png"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index?categorie=cocktail" >Cocktail</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index?categorie=plat">Plats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index?categorie=dessert">Desserts</a>
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
                <a href="./index.php"><img src="./img/logo_agraille.png"></a>
                </div>
                <div class="search-bar">
                    <form action="./source/page_filtrage" method="post">
                        <input class="search-input" type="text" name="nom" placeholder="Entrer un plat ou un ingrédient...">
                            <i class="search-input-icon fa fa-search"></i>
                        </input>
                    </form>
                </div>
                <a class="img_filtre" href="./source/page_filtrage.php"><img src="./img/filtre.png"/></a>
                <div class="d-grid gap-2 d-md-block">
                    <?php
                        if(isset($_SESSION['idCompte'])){        
                            if (preg_match('/JFIF/i',substr($insertP[0]['photo_de_profil'], 0, 10)))
                                echo '<img id="img_profil_pics" src="data:image/jpg;base64,' . base64_encode($insertP[0]['photo_de_profil']) . '">';
                            else if (preg_match('/GIF/i',substr($insertP[0]['photo_de_profil'], 0, 3)))
                                echo '<img id="img_profil_pics" src="data:image/gif;base64,' . base64_encode($insertP[0]['photo_de_profil']) . '">';
                            else if (preg_match('/PNG/i',substr($insertP[0]['photo_de_profil'], 1, 3)))
                                echo '<img id="img_profil_pics" src="data:image/png;base64,' . base64_encode($insertP[0]['photo_de_profil']) . '">';
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
            <li><?php echo $_SESSION["pseudo"]; ?></li>
            <li><a href="./source/profil.php">Voir profil</a></li>
            <li><a href="./source/confirmation_ingredient.php">Créer une recette</a></li>
            <?php
            if (isset($_SESSION['mode']))
                echo "<li><a href='./index?mode=change'>Changement de mode</a></li>";
            ?>
            <li><a onclick="location.href='./source/disconnect'" href="#">Se déconnecter</a></li>
        </ul>
    </div>
    <main>
        <div class='container my-2'>
        <div class='card-deck'>
            <?php 
                $i = 0;
                while($r = $recette->fetch()){
                    if (preg_match('/JFIF/i',substr($r['illustration'], 0, 10))){
                        $img = '<img id="img_card" class="card-img-top img-fluid" src="data:image/jpg;base64,' . base64_encode($r['illustration']) . '">';
                    }else if (preg_match('/GIF/i',substr($r['illustration'], 0, 3))){
                        $img = '<img id="img_card" class="card-img-top img-fluid" src="data:image/gif;base64,' . base64_encode($r['illustration']) . '">';
                    }else if (preg_match('/PNG/i',substr($r['illustration'], 1, 3))){
                        $img ='<img id="img_card" class="card-img-top img-fluid" src="data:image/png;base64,' . base64_encode($r['illustration']) . '">';
                    } 
                    $req = "SELECT pseudo FROM compte INNER JOIN compte_as_recette ON compte.id_compte = compte_as_recette.id_compte INNER JOIN recette ON recette.id_recette = compte_as_recette.id_recette;";
                    $auteure = $bdd->prepare($req);
                    $auteure->execute();
                    $auteur = $auteure->fetch();
                    $auteur = $auteur["pseudo"];
                    $nom = $r['nom'];
                    $id = $r['id_recette'];

                    echo("<div class='card'>$img<div class='card-body'><h5 class='card-title'>$nom</h5><p class='card-text'>Rédigé par $auteur</p></div><div class='card-footer text-center'><a href='./source/recette.php?id=$id'><div class='btn btn-primary'>J'veux la graille</div></a></div></div>");
                    

                };
            ?>
            </div>
        </div>   
    </main>
    <script src="./js/scriptIndex.js"></script>
</body>
</html>
