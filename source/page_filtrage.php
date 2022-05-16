<!DOCTYPE html>
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
    <link rel="stylesheet" href="../css/filtrage.css">
    <title>Filtrage</title>
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
                    <a href="connexion.php"><button type="button" class="btn btn-primary">Se connecter</button></a>
                    <a href="inscription.php"><button type="button" class="btn btn-primary">S'inscrire</button></a>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div id="main">
            <h2>Filtrage</h2><br/>
            <div class="filtre">
                <div id="p1">
                    Par temps de cuisson (en min)<br/><br/><input type="number" name="temps" min="0" max="180">
                </div>
                <div id="p2">
                    Par note (1 à 5)<br/><br/><input type="number" name="note" min="1" max="5">
                </div>
                <div id="p3">
                    Par nom<br/><br/><input type="text" name="name">
                </div>
            </div>
            <div id="p4">
                Par ingrédient<br/><br/><input type="text" name="name">
            </div>
        </div>
    </main>
</body>
</html>