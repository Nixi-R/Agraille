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
        $insertP = $bdd->prepare('SELECT photo_de_profil, mime FROM compte WHERE id =' .$_SESSION['idCompte']);
        $insertP -> execute();
        $insertP = $insertP->fetchAll();
    }
   
    
if(isset($_GET['id']) AND !empty($_GET['id'])){

    $getid = htmlspecialchars($_GET['id']);

    $description = $bdd->prepare('SELECT representation FROM recette WHERE id= ?');
    $description->execute(array($getid));
    $description = $description->fetch(PDO::FETCH_ASSOC);
    
    $title = $bdd->prepare('SELECT nom FROM recette WHERE id= ?');
    $title->execute(array($getid));
    $title = $title->fetch(PDO::FETCH_ASSOC);

    $image = $bdd->prepare('SELECT illustration FROM recette WHERE id= ?');
    $image->execute(array($getid));
    $image = $image->fetch(PDO::FETCH_ASSOC);


    if(isset($_POST['submit_commentaire'])) {
        if(isset($_POST['note'],$_POST['commentaire']) AND !empty($_POST['note']) AND !empty($_POST['commentaire'])){
            $id = random_int(0, 2147483647);
            $pseudo = htmlspecialchars($_SESSION['pseudo']);
            $commentaire = htmlspecialchars($_POST['commentaire']);
            $note = $_POST['note'];
            $ins = $bdd->prepare('INSERT INTO commentaire (id_commentaire, pseudo_commentaire, text_commentaire, date_commentaire, id_recette) VALUES (?,?,?,NOW(),?)');
            $ins->execute(array($id,$pseudo, $commentaire, $getid));
            // $insr = $bdd->prepare('INSERT INTO recette (note) VALUES (?)');
            // $insr->execute(array($note));
            $c_error = "Votre commentaire a bien été posté";
        }else {
            $c_error = "Tous les champs doivent être complétés";
        }
    }

    $commentaires = $bdd->prepare('SELECT * FROM commentaire WHERE id_recette = ? ORDER BY id_recette DESC');
    $commentaires->execute(array($getid));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/recette.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="icon" href="../img/icone_agraille2.png" sizes="any">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Agraille</title>
</head>
<body>
    <header>
        <nav>
            <div class="nav-burger">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <img src="../img/icone_agraille.png">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" >Apéritifs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Entrées</a>
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
                    <img onclick="location.href='../index.php'"src="../img/logo_agraille.png">
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
            <li><?php echo $_SESSION["pseudo"];?></li>
            <li><a href="./profil.php">Voir profil</a></li>
            <li><a href="#">Créer une recette</a></li>
            <li><a onclick="location.href='./disconnect'" href="#">Se déconnecter</a></li>
        </ul>
    </div>
    <main>
        <h2><?=$title['nom'];?></h2>
        <div id="wrapper">
        <section id="image_plat">
            <img src="../img/tartine.jpg">
            <p><?=$description['representation'];?></p>
            <div id="recette_info">
                 <span>25 min</span>
                 <span>3 étoiles</span>
                 <span>Au four</span>
                 <span>Facile</span>
            </div>
        </section>
        <section id="ingredient">
        </section>
        <section id="etape">
            <h2>Etapes</h2>
        </section>
        <section id="espace_commentaire">
            <h2>Commentaires</h2>
            <div class="stars">
               <i class="lar la-star" data-value="1"></i>
               <i class="lar la-star" data-value="2"></i>
               <i class="lar la-star" data-value="3"></i>
               <i class="lar la-star" data-value="4"></i>
               <i class="lar la-star" data-value="5"></i>
            </div>
            <script src="../js/scriptIndex.js"></script>
            <script src="../js/scriptNote.js"></script>
            <form method="POST" action="recette.php?id=<?=$getid;?>">
                <input type="hidden" name="note" id="note" value="0">
                <textarea name="commentaire" placeholder="Votre commentaire..."></textarea>
                <input type="submit" value="valider" name="submit_commentaire">
            </form>
            <?php if(isset($c_error)){echo $c_error;}?>
            <?php while($c = $commentaires->fetch()){ ?>
            <b><?= $c['pseudo_commentaire']?>:</b> <?= $c['text_commentaire']; ?></b>
            <?php } ?>
        </div>
    </main>
</body>
</html>