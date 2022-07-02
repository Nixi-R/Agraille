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
    }

    if (isset($_SESSION['mode']) && isset($_GET['mode']))
        if ($_SESSION['mode'] == 1)
            $_SESSION['mode'] = 0;
        else
            $_SESSION['mode'] = 1;

            $ingredient = $bdd->prepare('SELECT * FROM ingredient');
            $ingredient->execute();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/filtrage.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="../img/icone_agraille2.png" sizes="any">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Agraille|Filtrage</title>
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
                        <a class="nav-link" href="../index?categorie=cocktail" >Cocktails</a>
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
                    <p class="filtreIcons flexCentre">📝</p>
                    <div class="filtreTexte flexCentre">Par nom<input type="text" name="nom"></div>
                </div>
                <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">📆</p>
                    <div class="filtreTexte flexCentre">Par date de publication<input type="date" name="date"></div>
                </div>   
                <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">⏱️</p>
                    <div class="filtreTexte flexCentre">Par temps de réalisation (en min)<input type="time" name="temps" min="0" max="180"></div>
                </div>
                <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">♨️</p>
                    <div class="filtreTexte flexCentre">Par méthode de cuisson
                        <select require id="methode_cuisson" name="methode">
                            <option></option>
                            <option>Marinade</option>
                            <option>Bouillir</option>
                            <option>Mijoter</option>
                            <option>A la poêle</option>
                            <option> A la marmite</option>
                            <option>Au four</option>
                        </select>
                    </div>
                </div>
                <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">📚</p>
                    <div class="filtreTexte flexCentre">Par auteur<input type="text" name="auteur"></div>
                </div>
                <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">⭐</p>
                    <div class="filtreTexte flexCentre">Par note (1 à 5)<input type="number" name="note" min="1" max="5"></div>
                </div>
                <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">🍹</p>
                    <div class="filtreTexte flexCentre"><label for="type">Par type</label>
                        <select name="type" id="types">
                            <option></option>
                            <option name="plat">Plat</option>
                            <option name="cocktail">Cocktail</option>
                            <option name="dessert">Dessert</option>
                        </select>
                    </div>
                </div>
                <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">🌡️</p>
                    <div class="filtreTexte flexCentre"><label for="difficulte">Par difficulté</label>
                        <select name="difficulte" id="difficulte">
                            <option></option>
                            <option name="facile">Facile</option>
                            <option name="intermédiaire">Intermédiaire</option>
                            <option name="difficile">Difficile</option>
                        </select>
                    </div>
                </div>
                <!-- <div class="flexBetween filtreTexteIcons">
                    <p class="filtreIcons flexCentre">🍒</p>
                    <div class="filtreTexte flexCentre">Par ingrédient
                         <select type='hidden'name="ingredients">
                        </select> -->
                    <!-- </div> -->
                <!-- </div>  -->
                <div class="bouton flexCentre"><input type="submit" value="Chercher🔎"></div>
            </form>
            </div>
            <script src="../js/scriptIndex.js"></script>
            <?php
                $test = 0;
                if (isset($_POST['nom'])){
                    $nom = $_POST['nom'];
                }
                if (isset($_POST['date'])){
                    $date = $_POST['date'];
                }
                if (isset($_POST['temps'])){
                    $temps = $_POST['temps'];
                }
                if (isset($_POST['methode']) ){
                    $methode = $_POST['methode'];
                }
                if (isset($_POST['auteur']) ){
                    $auteur = $_POST['auteur'];
                }
                if (isset($_POST['note']) ){
                    $note = $_POST['note'];
                }
                if (isset($_POST['type']) ){
                    $type = $_POST['type'];
                }
                if (isset($_POST['difficulte']) ){
                    $difficulte = $_POST['difficulte'];
                }
                // if ($_POST['ingredients'] != null ){
                //     $ingredients = $_POST['ingredients'];
                // }
                $SQL = "SELECT * FROM recette ";

                if (isset($auteur) && $auteur != null){
                    $req = $bdd->prepare("SELECT compte.id_compte FROM compte INNER JOIN compte_as_recette ON compte.id_compte = compte_as_recette.id_compte INNER JOIN recette ON recette.id_recette = compte_as_recette.id_recette WHERE pseudo = '". $auteur ."'" );
                    $req->execute();
                    $req = $req->fetchAll();

                    $SQL = $SQL . " INNER JOIN compte_as_recette ON ". $req[0]['id_compte'] ." = compte_as_recette.id_compte AND compte_as_recette.id_recette = recette.id_recette ";
        
                    $test = 1;
                }

                if (isset($temps) && $temps != null){
                    if ($test == 1){
                        $SQL = $SQL . "AND (temps_realisation <= '" . $temps . "')";
                    }else {
                        $SQL = $SQL . "WHERE (temps_realisation <= '" . $temps . "')";
                    }
                    $test = 1;
                }

                if (isset($note) && $note != null){
                    if ($test == 1){
                        $SQL = $SQL . " AND (note = " . $note . ")";
                    } else {
                        $SQL = $SQL . "WHERE (note = " . $note . ")";
                    }
                    $test = 1;
                    
                }

                if (isset($nom) && $nom != null){
                    if ($test == 1){
                        $SQL = $SQL . " AND (nom LIKE '" . $nom . "%')";
                    } else {
                        $SQL = $SQL . "WHERE (nom LIKE '" . $nom . "%')";
                    }
                    $test = 1;
                }

                if (isset($methode) && $methode != null){
                    if ($test == 1){
                        $SQL = $SQL . " AND (methode_cuisson = '" . $methode . "')";
                    } else {
                        $SQL = $SQL . "WHERE (methode_cuisson = '" . $methode . "')";
                    }
                    $test = 1;
                }
                
                if (isset($type) && !(empty($type))){
                    if ($test == 1){
                            $SQL = $SQL . " AND (categorie = '" . $type . "')";
                    }
                    else {
                            $SQL = $SQL . "WHERE (categorie = '" . $type . "')";
                    }
                    $test = 1;
                }
                
                if (isset($difficulte) && !(empty($difficulte))){
                    if ($test == 1){
                            $SQL = $SQL . " AND (difficulte = '" . $difficulte . "')";
                        }
                    else {
                            $SQL = $SQL . "WHERE (difficulte = '" . $difficulte . "')";
                    }
                    $test = 1;
                }

                if (isset($date) && $date != null){
                    if ($test == 1){
                        $SQL = $SQL . " AND (date_publication = '" . $date . "')";
                    } else {
                        $SQL = $SQL . "WHERE (date_publication = '" . $date . "')";
                    }
                    $test = 1;
                }
                // if (isset($ingredients) && $ingredients != null){
                //     if ($test == 1){
                //         $SQL = $SQL . " AND (ingredients = '" . $ingredients . "')";
                //     } else {
                //         $SQL = $SQL . "(ingredients = '" . $ingredients . "')";
                //     }
                // }

                if (!(empty($_POST)) && count($_POST) == 1 && strlen($_POST['nom']) > 0)
                {
                    if (isset($_SESSION['mode']) && $_SESSION['mode'] == 1)
                    {
                        $SQL = $SQL . " AND valider = 0 ORDER BY id_recette DESC";
                        $SQL = $bdd->prepare($SQL);
                        $SQL->execute();
                        $SQL = $SQL->fetchAll();
                    }
                    else
                    {
                        $SQL = $SQL . " AND valider = 1 ORDER BY id_recette DESC";
                        $SQL = $bdd->prepare($SQL);
                        $SQL->execute();
                        $SQL = $SQL->fetchAll();
                    }
                    $o = " "; 
                }

                else if(!(empty($_POST)) && count($_POST) > 1 && (strlen($_POST['nom']) + strlen($_POST['date']) + strlen($_POST['temps']) + strlen($_POST['methode']) + strlen($_POST['auteur']) + strlen($_POST['note']) + strlen($_POST['type']) + strlen($_POST['difficulte'])) > 0)
                {
                    if (isset($_SESSION['mode']) && $_SESSION['mode'] == 1)
                    {
                        $SQL = $SQL . " AND valider = 0 ORDER BY recette.id_recette DESC";
                        $SQL = $bdd->prepare($SQL);
                        $SQL->execute();
                        $SQL = $SQL->fetchAll();
                    }
                    else
                    {
                        $SQL = $SQL . " AND valider = 1 ORDER BY recette.id_recette DESC";
                        $SQL = $bdd->prepare($SQL);
                        $SQL->execute();
                        $SQL = $SQL->fetchAll();
                    }
                    $o = " "; 
                }

                if (isset($o))
                {
                    for($i=0; $i<count($SQL); $i++){
                        $id = $SQL[$i]['id_recette'];

                        if (preg_match('/GIF/i',substr($SQL[$i]['illustration'], 0, 3))){
                            $img = '<img id="img_card" class="card-img-top img-fluid" src="data:image/gif;base64,' . base64_encode($SQL[$i]['illustration']) . '">';
                        }else if (preg_match('/PNG/i',substr($SQL[$i]['illustration'], 1, 3))){
                            $img ='<img id="img_card" class="card-img-top img-fluid" src="data:image/png;base64,' . base64_encode($SQL[$i]['illustration']) . '">';
                        } 
                        else{
                            $img = '<img id="img_card" class="card-img-top img-fluid" src="data:image/jpg;base64,' . base64_encode($SQL[$i]['illustration']) . '">';
                        }

                        $req = $bdd->prepare("SELECT pseudo FROM compte INNER JOIN compte_as_recette ON compte.id_compte = compte_as_recette.id_compte INNER JOIN recette ON $id = compte_as_recette.id_recette");
                        $req->execute();
                        $req = $req->fetchAll();

                        $auteur = $req[$i]['pseudo'];
                        $nom = $SQL[$i]['nom'];
                        $button = `<button onclick="location.href='./recette.php?id=$id'" class="btn btn-primary">J'veux le graille !</button>`;
        
                        echo("<div class='container my-2'>
                        <div class='card-deck'>
                                <div class='card'>
                                    $img
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
                }
            ?>
        </div>
    </main>
    <script src="../js/scriptIndex.js"></script>
</body>
</html>