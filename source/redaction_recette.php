<?php
session_start();

try{
    $conn = new PDO(
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
        $insertP = $conn->prepare('SELECT photo_de_profil, mime FROM compte WHERE id ='. $_SESSION['idCompte'] );
        $insertP -> execute();
        $insertP = $insertP->fetchAll();
    }

    $ingredient = $conn->prepare('SELECT * FROM ingredient');
    $ingredient->execute();

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
    <link rel="stylesheet" href="../css/redaction_recette.css">
    <title>Agraille|Rédiger votre recette !</title>
</head>
<body>
<header>
        <nav>
            <div class="nav-burger">
                <ul class="nav-menu">
                <li class="nav-item">
                       <a href="./index.php"><img src="../img/icone_agraille.png"></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index?categorie=cocktail" >Cocktail</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index?categorie=plats">Plats</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index?categorie=dessert">Desserts</a>
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
            <li><a onclick="location.href='./redaction_recette'">Créer une recette</a></li>
            <li><a onclick="location.href='./disconnect'">Se déconnecter</a></li>
        </ul>
    </div>
    <main>
        <form action="./envoi_recette.php" method="post" name="myForm" onsubmit>
            <h1>Rédigez votre propre recette !</h1>
            <section id="titre">
                <h6>Nommez la recette !<h6>
                <input require id="titre_input" type="text" name ="title"></input> 
            </section>
            <section id="description" name ="description">
                <h6>Faites nous une description de la recette !<h6>
                <textarea require type="text" id="desc_input" name="description"></textarea>
            </section>
            <section id="ingredient_container">
                <h6>Les ingredients<h6>
                <div id="ingredient_info_1">
                <div class="tag-container">
                    <p>Il n'y a pas les ingrédients utilisé dans votre recette ? Faites nous des propositions !<p>
                        <?php 
                        $var = array();
                        $x = 0;
                        while($j = $ingredient->fetch()){
                            $var[$x] = "<option>".$j["ingredient"]."</option>";
                            $x++;
                        }
                        if(isset($_REQUEST["ingredient_nombre"])){
                            $n = $_REQUEST["ingredient_nombre"];
                            for($i = 0; $i<$n; $i++){
                                echo "<select name='mesure_$i'>
                                <option>Aucune</option>
                                <option>centilitres</option>
                                <option>litres</option>
                                <option>grammes</option>
                                <option>kilos</option>
                                <option>grammes</option>
                                <option>cuil à soupe</option>
                                <option>cuil à café</option>
                                </select>
                                <input name='quantite_$i' type='number' min='0' max='100'> 
                                <select name='ingredient_$i'>";
                                for($z = 0; $z<count($var); $z++){
                                    echo $var[$z];
                                }
                                // echo $var[0];
                                // echo $var[1];
                                echo "</select><br>";
                            }
                        }else{
                                header("Location: ./confirmation_ingredient");
                            }

                            
                        ?>
                    </select>
                </div>
            </section>
            <section id="etape">
                <h6>Décrivez les étapes !</h6>
                <input require type="text" id="step_1" placeholder="1 - Décrivez l'étape" name="step_1"></input>
                <div id="etapes"></div>
                <span id="etape_button" class="btn btn-primary" value="+" >+</span>
            </section>
            <section id="recette_info">
                <h6>D'autres informations concernant la recette...</h6>
                <p>Methode de cuisson.<p>
                <select require id="methode_cuisson" name="methode_cuisson">
                    <option>Aucune</option>
                    <option>Marinade</option>
                    <option>Bouillir</option>
                    <option>Mijoter</option>
                    <option>A la poêle</option>
                    <option> A la marmite</option>
                    <option>Au four</option>
                </select>
                <span class="span"></span>
                <p>Entrez le temps de réalisation de la recette.<p>
                <input require id="temps_realisation" type ="number" min="10" max="180" name="temps_realisation"><span>min</span>
                <span class="span"></span>
                <p>Selectionnez la difficulté de la recette.<p>
                <select require id="difficulte" name="difficulte">
                    <option>Facile</option>
                    <option>Intermédiaire</option>
                    <option>Difficile</option>
                </select>
                <span class="span"></span>
                <p>Selectionnez la catégorie de la recette.<p>
                <select require id="type_recette" name="type_recette">
                    <option>Plat</option>
                    <option>Cocktail</option>
                    <option>Dessert</option>
                </select>
                <h6>Choisissez une illustration pour votre recette !</h6>
                <input id="recette_image" name="recette_image" type="file" accept="image/*"></input>
            </section>
            <input type="submit" onclick="document.recette_envoi.submit()"></input>
        </form>
    </main>
    <script src="../js/scriptIndex.js"></script>
    <script>
        window.onload = function(){
            let button = document.getElementById('etape_button');
            let step = document.getElementById('etapes');
            let i = 1;

            button.addEventListener('click', function(event){
                i++
                if (button.textContent === '+'){
                    let newInput = document.createElement('input')
                        newInput.type = 'text'
                        newInput.placeholder = i + " - Décrivez l'étape";
                        newInput.id = 'step_'+i;
                        newInput.name = "step_" + i;
                    step.appendChild(newInput);
                }
            })
          }
    </script>
</body>
</html>