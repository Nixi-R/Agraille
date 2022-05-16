<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="./img/icone_agraille.png" sizes="any">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Agraille</title>
</head>
<body>
    <header>
        <nav>
            <div class="nav-burger">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <img src="img/icone_agraille.png">
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
                    <img src="./img/logo_agraille.png">
                </div>
                <div class="search-bar">
                    <form action="#" >
                        <input class="search-input" type="text" name="filtrage" placeholder="Entrer un plat ou un ingrédient...">
                            <i class="search-input-icon fa fa-search"></i>
                        </input>
                    </form>
                </div>
                <div class="d-grid gap-2 d-md-block">
                        <a href='./source/connexion.php'><button type='button' class='btn btn-primary'>Se connecter</button></a>
                        <a href='./source/inscription.php'><button type='button' class='btn btn-primary'>S'inscrire</button></a>
                </div>
            </div>
        </nav>
    </header>
    <div class="profil_menu">
        <ul>
            <li><?php echo $_SESSION["pseudo"] ?></li>
            <li><a href="./source/profil.php">Voir profil</a></li>
            <li><a href="#">Créer une recette</a></li>
            <li><a onclick="location.href='./source/disconnect'" href="#">Se déconnecter</a></li>
        </ul>
    </div>
    <main>
        <div id="main">
               <div class="container my-2">
                   <div class="card-deck">
                        <div class="card">
                            <img class="card-img-top img-fluid" src="https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.thailandveo.com%2Fwp-content%2Fuploads%2Fsites%2F3%2F2019%2F04%2FAdobeStock_207701012-min.jpeg&f=1&nofb=1" alt="#">
                            <div class="card-body">
                                <h5 class="card-title">Recette_1</h5>
                                <p class="card-text">Rédiger par <?$pseudo?></p>
                            </div>
                            <div class="card-footer text-center">
                                <button class="btn btn-primary">J'veux le graille !</button>
                            </div>
                        </div>
                   </div>
               </div>     
        </div>
    </main>
    <script src="./js/scriptIndex.js"></script>
</body>
</html>