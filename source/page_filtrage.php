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
        $insertP = $bdd->prepare('SELECT photo_de_profil, mime FROM compte WHERE id ='. $_SESSION['idCompte'] );
        $insertP -> execute();
        $insertP = $insertP->fetchAll();
    }

    if (isset($_SESSION['mode']) && isset($_GET['mode']))
        if ($_SESSION['mode'] == 1)
            $_SESSION['mode'] = 0;
        else
            $_SESSION['mode'] = 1;
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="../img/icone_agraille2.png" sizes="any">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Agraille|<?php if (isset($_SESSION['mode'])  && $_SESSION['mode'] == 1) echo "Validation"; else echo "Accueil";?></title>
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
                    <img src="../img/logo_agraille.png">
                </div>
                <div class="search-bar">
                    <form action="#" >
                        <input class="search-input" type="text" name="filtrage" placeholder="Entrer un plat ou un ingrédient...">
                            <i class="search-input-icon fa fa-search"></i>
                        </input>
                    </form>
                </div>
                <a class="img_filtre" href="./page_filtrage.php"><img src="../img/filtre.png"/></a>
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
            <li><?php echo $_SESSION["pseudo"]; ?></li>
            <li><a href="./profil.php">Voir profil</a></li>
            <li><a href="./redaction_recette.php">Créer une recette</a></li>
            <?php
            if (isset($_SESSION['mode']))
                echo "<li><a href='./page_filtrage?mode=change'>Changement de mode</a></li>";
            ?>
            <li><a onclick="location.href='./disconnect?page=filtre'" href="#">Se déconnecter</a></li>
        </ul>
    </div>
    <main>
        <div id="main">
            <div class="margFiltreTitre"><h2>Filtrage</h2></div>
            <div class="filtre margFiltre flexCentre"> 
            <form action="#" method="post">   
                <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">⏱️</p>
                    <div class="filtreTexte flexCentre">Par temps de cuisson (en min)<input type="number" name="temps" min="0" max="180"></div>
                </div>
                <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">⭐</p>
                    <div class="filtreTexte flexCentre">Par note (1 à 5)<input type="number" name="note" min="1" max="5"></div>
                </div>
                <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">📝</p>
                    <div class="filtreTexte flexCentre">Par nom<input type="text" name="nom"></div>
                </div>
                <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">🍒</p>
                    <div class="filtreTexte flexCentre">Par ingrédient<input type="text" name="ingredients"></div>
                </div>
                <input type="submit" value="Chercher">
            </form>
            </div>
            <?php
                $test = 0;
                if ($_POST['temps'] != null ){
                    $temps = $_POST['temps'];
                }
                if ($_POST['note'] != null ){
                    $note = $_POST['note'];
                }
                if ($_POST['nom'] != null ){
                    $nom = $_POST['nom'];
                }
                if ($_POST['ingredients'] != null ){
                    $ingredients = $_POST['ingredients'];
                }

                $SQL = "SELECT * FROM recette WHERE ";
                
                if (isset($temps) && $temps != null){
                    $SQL = $SQL . "temps_realisation <= " . $temps ;
                    $test = 1;
                }

                if (isset($note) && $note != null){
                    if ($test == 1){
                        $SQL = $SQL . "AND note = " . $note ;
                    } else {
                        $SQL = $SQL . "note = " . $note ;
                    }
                    $test = 1;
                    
                }
                if (isset($nom) && $nom != null){
                    if ($test == 1){
                        $SQL = $SQL . "AND nom LIKE " . $nom ;
                    } else {
                        $SQL = $SQL . "nom LIKE " . $nom ;
                    }
                    $test = 1;
                }

                if (isset($ingredients) && $ingredients != null){
                    if ($test == 1){
                        $SQL = $SQL . "AND ingredients IN " . $ingredients . " AND *";
                    } else {
                        $SQL = $SQL . "ingredients IN " . $ingredients . " AND *";
                    }
                }
                
                if (isset($_SESSION['mode']) && $_SESSION['mode'] == 1)
                {
                    $SQL = $SQL . " AND valider = 0 ORDER BY id DESC";
                    $SQL = $bdd->prepare($SQL);
                }
                else
                {
                    $SQL = $SQL . " AND valider = 1 ORDER BY id DESC";
                    $SQL = $bdd->prepare($SQL);
                }
                $SQL->execute();
                $SQL = $SQL->fetchAll();

                for($i=0; $i<count($SQL); $i++){
                    $auteur = $SQL[$i]['auteur'];
                    $nom = $SQL[$i]['nom'];
                    $id = $SQL[$i]['id'];
                    $button = `<button onclick="location.href='./recette.php?id=$id'" class="btn btn-primary">J'veux le graille !</button>`;
    
                    echo("<div class='container my-2'>
                       <div class='card-deck'>
                            <div class='card'>
                                <img class='card-img-top img-fluid' src='https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.thailandveo.com%2Fwp-content%2Fuploads%2Fsites%2F3%2F2019%2F04%2FAdobeStock_207701012-min.jpeg&f=1&nofb=1' alt='#'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$nom</h5>
                                    <p class='card-text'>Rédigé par $auteur</p>
                                </div>
                                <div class='card-footer text-center'>
                               <a href='./recette.php?id=$id'><button id='recette_button' class='btn btn-primary'>J'veux le graille !</button></a>
                                </div>
                            </div>
                       </div>
                   </div>");
                }; 
            ?>
        </div>
    </main>
    <script src="../js/scriptIndex.js"></script>
</body>
</html>