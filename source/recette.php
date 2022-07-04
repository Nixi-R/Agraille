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

    $valider = $bdd->prepare('SELECT valider FROM recette where id_recette = '.$_GET["id"]);
    $valider -> execute();
    $valider = $valider->fetch();
    if($valider == false)
    {
        header("Location: ../404.php?erreur=cette recette n'existe pas");
    }
    
    if (isset($_SESSION['mode']) && $_SESSION['mode'] == 1)
    {
        if ($valider['valider'] == 1)
                header('Location: ../404.php?erreur=recette déjà validée');
        else
            $admin = true;
    }
    else 
    {
        $admin = 0;
        if ($valider['valider'] == 0)
            header('Location: ../404.php?erreur=recette non validée');
    }



    if (isset($_SESSION['idCompte']))
    {  
        $insertP = $bdd->prepare('SELECT photo_de_profil FROM compte WHERE id_compte =' .$_SESSION['idCompte']);
        $insertP -> execute();
        $insertP = $insertP->fetchAll();
    }
    
    
if(isset($_GET['id']) AND !empty($_GET['id'])){

    $getid = htmlspecialchars($_GET['id']);

    $recette = $bdd->prepare('SELECT * FROM recette WHERE id_recette= ?');
    $recette->execute(array($getid));
    $recette = $recette->fetch(PDO::FETCH_ASSOC);
    

    $compteasrecette = $bdd->prepare('SELECT id_compte_as_recette FROM compte_as_recette WHERE id_recette = ?');
    $compteasrecette->execute(array($getid));
    $compteasrecette = $compteasrecette->fetch(PDO::FETCH_ASSOC);

    
    $compteur = $bdd->prepare("SELECT COUNT(note) FROM note WHERE (id_recette = ?)");
    $compteur->execute(array($getid)); 
    $compteur = $compteur->fetch(PDO::FETCH_ASSOC);

    $note = $bdd->prepare("SELECT AVG(note) FROM note WHERE (id_recette = ?)");
    $note->execute(array($getid));
    $note = $note->fetch(PDO::FETCH_ASSOC);

    $req = "SELECT pseudo FROM compte INNER JOIN compte_as_recette ON compte.id_compte = compte_as_recette.id_compte INNER JOIN recette ON recette.id_recette = compte_as_recette.id_recette;";
    $auteure = $bdd->prepare($req);
    $auteure->execute();
    $auteur = $auteure->fetch();

    $req = $bdd->prepare("SELECT ingredient.ingredient FROM ingredient INNER JOIN recette_as_ingredient ON recette_as_ingredient.id_ingredient = ingredient.id_ingredient WHERE recette_as_ingredient.id_recette = ". $getid ." AND valider = 1");
    $req->execute();
    $req = $req->fetchAll();

    $ingr_valider = $bdd->prepare("SELECT ingredient.ingredient, ingredient.id_ingredient FROM ingredient INNER JOIN recette_as_ingredient ON recette_as_ingredient.id_ingredient = ingredient.id_ingredient WHERE recette_as_ingredient.id_recette = ". $getid ." AND valider = 0");
    $ingr_valider->execute();
    $ingr_valider = $ingr_valider->fetchAll();

    for ($o = 0; $o < count($ingr_valider); $o++)
    {
        if(isset($_POST['valider_'.$o]) && $_POST['valider_'.$o] = "valider")
        {
            $validat = $bdd->prepare("UPDATE ingredient SET valider = 1 WHERE id_ingredient = " . $ingr_valider[$o][1]);
            $validat-> execute();
        }
        else if (isset($_POST['refuser_'.$o]) && $_POST['refuser_'.$o] = "refuser")
        {
            $validat = $bdd->prepare("DELETE i FROM ingredient i INNER JOIN recette_as_ingredient ON recette_as_ingredient.id_ingredient = i.id_ingredient WHERE i.valider = 0 AND recette_as_ingredient.id_recette = " . $getid);
            $validat-> execute();
            $validat = $bdd->prepare("DELETE FROM recette WHERE id_recette = " . $getid);
            $validat-> execute();
            $validat = $bdd->prepare("DELETE FROM recette_as_ingredient WHERE id_recette = " . $getid);
            $validat-> execute();
            $temp = fopen("../blacklist_ingredient.txt", "rw+");
            fwrite($temp, $ingr_valider[$o][0] . ",");
            header("Location: ../404.php?erreur=cette recette n'existe pas");
        }
    }

    
    $req = $bdd->prepare("SELECT ingredient.ingredient FROM ingredient INNER JOIN recette_as_ingredient ON recette_as_ingredient.id_ingredient = ingredient.id_ingredient WHERE recette_as_ingredient.id_recette = ". $getid ." AND valider = 1");
    $req->execute();
    $req = $req->fetchAll();

    $ingr_valider = $bdd->prepare("SELECT ingredient.ingredient, ingredient.id_ingredient FROM ingredient INNER JOIN recette_as_ingredient ON recette_as_ingredient.id_ingredient = ingredient.id_ingredient WHERE recette_as_ingredient.id_recette = ". $getid ." AND valider = 0");
    $ingr_valider->execute();
    $ingr_valider = $ingr_valider->fetchAll();

    $title = $recette['nom'];
    $description = $recette['representation'];

    if ($valider['valider'] == 1)
    {
        if(isset($_POST['submit_note'])){
            if(isset($_POST['note']) AND !empty($_POST['note'])){
                $id = random_int(0, 2147483647);
                $pseudo = htmlspecialchars($_SESSION['pseudo']);

                $note_verif =$bdd->prepare("SELECT note FROM note WHERE (id_compte = ?) AND (id_recette = ?)");
                $note_verif->execute(array($_SESSION["idCompte"],$getid,));
                $resulte = $note_verif->fetch(PDO::FETCH_ASSOC);
            
                if(empty($resulte)){
                    $note_req = $bdd->prepare('INSERT INTO note (id_note, id_recette, id_compte,note) VALUES (?,?,?,?)');
                    $note_req->execute(array($id,$getid,$_SESSION["idCompte"],$_POST["note"]));
                    header("Location: recette?id=$getid");
                    exit();
                }else{
                    $note_req = $bdd->prepare('UPDATE note SET note = ? WHERE id_compte = ? AND id_recette = ?');
                    $note_req->execute(array($_POST["note"],$_SESSION["idCompte"],$getid));
                }
            }
        }

        if(isset($_POST['submit_commentaire'])) {
            if(isset($_POST['commentaire'])  AND !empty($_POST['commentaire'])){
                $id = random_int(0, 2147483647);
                $pseudo = htmlspecialchars($_SESSION['pseudo']);
                $commentaire = htmlspecialchars($_POST['commentaire']);


                $ins = $bdd->prepare('INSERT INTO commentaire (id_commentaire, texte_commentaire, date_commentaire, id_compte_as_recette,id_compte) VALUES (?,?,NOW(),?,?)');
                $ins->execute(array($id, $commentaire,$getid,$_SESSION["idCompte"]));
                
                $req = $bdd->prepare('INSERT INTO compte_as_recette (id_compte_as_recette,id_compte,id_recette) VALUES(?,?,?)');
                $req->execute(array($id,$_SESSION["idCompte"],$getid));

                $c_error = "Votre commentaire a bien été posté";
                header("Location: recette?id=$getid");
                exit();
            }else {
                $c_error = "Tous les champs doivent être complétés";
            }
        }
        

        $commentaires = $bdd->prepare('SELECT * FROM commentaire INNER JOIN compte_as_recette ON commentaire.id_compte_as_recette = compte_as_recette.id_compte_as_recette WHERE compte_as_recette.id_recette = ? ORDER BY commentaire.id_commentaire DESC');
        $commentaires->execute(array($getid));
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
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/recette.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="icon" href="../img/icone_agraille2.png" sizes="any">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <?php if ($admin) echo "<link rel='stylesheet' href='../css/valid-recette.css'>"; ?>
    <title>Agraille</title>
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
                    <form action="./page_filtrage" method="post">
                        <input class="search-input" type="text" name="nom" placeholder="Entrer un plat...">
                            <i class="search-input-icon fa fa-search"></i>
                        </input>
                    </form>
                </div>
                <a class="img_filtre" href="./page_filtrage.php"><img id="img_filtre" src="../img/filtre.png"/></a>
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
            <li><?php echo "<b>Bonjour ".$_SESSION["pseudo"]."!</b>"; ?></li>
            <li><a href="./profil.php">Voir profil</a></li>
            <li><a href="./confirmation_ingredient.php">Créer une recette</a></li>
            <?php
            if (isset($_SESSION['mode']))
                echo "<li><a href='../index?mode=change'>Changement de mode</a></li>";
            ?>
            <li><a onclick="location.href='./disconnect'" href="#">Se déconnecter</a></li>
        </ul>
    </div>
    <main>
        <?php 
        if ($admin) 
            echo '<form id="form" enctype="multipart/form-data" method="POST" action="./upload_recette.php" id="form_modif">
            <input type="text" id="title" name="nom" value="'.$title.'">'; 
        else 
            echo "<h1>".$title."</h1>";?>
        <div id="wrapper">
        <section id="image_plat">
            <?php if (isset($_SESSION['mode']) && $_SESSION['mode'] == 1) 
            echo "<div id='modif_illu'>";

            if ($recette['illustration'] == null && isset($_SESSION['mode']) && $_SESSION['mode'] == 0) 
                echo '<img src="../img/tartine.jpg">'; 
            else if($recette['illustration'] == null && !(isset($_SESSION['mode'])))
                echo '<img src="../img/tartine.jpg">'; 
            else if ($recette['illustration'] != null && isset($_SESSION['mode']) && $_SESSION['mode'] == 0){   
                if (preg_match('/GIF/i',substr($recette["illustration"], 0, 3)))
                    echo '<img id="recette_image" src="data:image/gif;base64,' . base64_encode($recette["illustration"]) . '"/>';
                else if (preg_match('/PNG/i',substr($recette["illustration"], 1, 3)))
                    echo '<img id="recette_image" src="data:image/png;base64,' . base64_encode($recette["illustration"]) . '"/>';
                else
                    echo '<img id="recette_image" src="data:image/jpg;base64,' . base64_encode($recette["illustration"]) . '"/>';
                echo "<p>auteur : " .$auteur["pseudo"] ."</p>";
            }
            else if ($recette['illustration'] != null && !(isset($_SESSION['mode'])))
            {
                if (preg_match('/GIF/i',substr($recette["illustration"], 0, 3)))
                    echo '<img id="recette_image" src="data:image/gif;base64,' . base64_encode($recette["illustration"]) . '"/>';
                else if (preg_match('/PNG/i',substr($recette["illustration"], 1, 3)))
                    echo '<img id="recette_image" src="data:image/png;base64,' . base64_encode($recette["illustration"]) . '"/>';
                else
                    echo '<img id="recette_image" src="data:image/jpg;base64,' . base64_encode($recette["illustration"]) . '"/>';
                echo "<p>auteur : " .$auteur["pseudo"] ."</p>";
            }
            else if (isset($_SESSION['mode']) && $_SESSION['mode'] == 1)
                echo "<input id='file' type='file' name='photo' accept='image/png, image/jpeg, image/gif, image/jpg' >"; 
            ?>
            </div>
            <?php 
            if ($admin) 
                echo '<label>Description</label><textarea id="description" name="representation" form="form">'.$description.'</textarea>'; 
            else 
                echo '<p>'.$description.'</p><div id="recette_info">'; ?>
            
            <?php
            if ($admin)
                echo "<label>Temps de réalisation</label></br><input id='tps_real' type='text' name='temps_realisation' value='m".$recette['temps_realisation']."'></br>
                <label>Méthode de cuisson</label></br><input type='text' name='methode_cuisson' value='".$recette['methode_cuisson']."'></br>
                <label>Difficulté</label></br><input type='text' name='difficulte' value='".$recette['difficulte']."'></br>
                <label>Type de recette</label></br><input type='text' name='categorie' value='".$recette['categorie']."'></br>
                <input type='hidden' name='id' value='".$_GET['id']."'>";
            else
            {   
                echo "<div id='tps_realisation'><label>Temps de réalisation : </label><p>".$recette['temps_realisation']."</p></div>
                <div id='note1'><label>Note de la recette : </label><p>" .$note['AVG(note)'] ."</p><p>Nombre de personnes ayant noté la recette: " .$compteur['COUNT(note)'] .".</p></div>
                <div id='methode_cuisson'><label>La méthode de cuisson : </label><p>".$recette['methode_cuisson']."</p></div>
                <div id='difficulte'><label>La difficulté de la recette : </label><p>".$recette['difficulte']."</p></div>";
            }
            ?>

        </div>
        </section>
        <section id="ingredient">
            <h2>Ingredients</h2>
            <?php
            if ($admin){
                for ($l = 0; $l < count($req); $l++ ){
                echo "<input type='text' id='ingredients' name='ingredients_$l' value='". $req[$l][0] ."'>";
                }
                for ($o = 0; $o < count($ingr_valider); $o++ ){
                    echo "<input type='text' id='ingredients' name='ingredients_$o' value='". $ingr_valider[$o][0] ."'>
                    <input type='submit' id='ingredients' name='valider_$o' value='valider' formaction='#'>
                    <input type='submit' id='ingredients' name='refuser_$o' value='refuser' formaction='#'>";
                }
            }
            else 
                echo "<p>".$recette['ingredient']."</p>";
            ?>
        </section>
        <section id="etape">
            <h2>Etapes</h2>
            <?php
            if ($admin)
                echo "<textarea id='etapes' name='etape' form='form'>".$recette['etape']."</textarea>";
            else
            $etape = array();
            $etape= explode(".", $recette['etape']);

            for($i = 0; $i < count($etape); $i++ ){
                $j = $i+1;
                print_r("<p>".$j ."- ".$etape[$i]."</p>");
            }
            ?>
        </section>
        <?php if ($valider['valider'] == 1 && isset($_SESSION['idCompte']))
        echo '<section id="espace_commentaire">
            <h2>Note</h2>
            <div class="stars">
               <i class="lar la-star" data-value="1"></i>
               <i class="lar la-star" data-value="2"></i>
               <i class="lar la-star" data-value="3"></i>
               <i class="lar la-star" data-value="4"></i>
               <i class="lar la-star" data-value="5"></i>
            </div>
            <form method="POST" action="recette.php?id='.$getid.'">
                <input type="hidden" name="note" id="note" value="0">
                <input id="sub_note" type="submit" name="submit_note" />
            </form>
            <h2>Commentaires</h2>
            <form method="POST" action="recette.php?id='.$getid.'">
                <textarea name="commentaire" placeholder="Votre commentaire..."></textarea>
                <input id="sub_com" type="submit" value="valider" name="submit_commentaire">
                <script src="../js/scriptNote.js"></script>
            </form>';
          ?>
            <?php if(isset($c_error)){echo $c_error;}?>
            <?php if ($valider['valider'] == 1) {while($c = $commentaires->fetch()){ ?>
            <b><?php
                    $req_com = $bdd->prepare("SELECT pseudo FROM compte INNER JOIN commentaire ON compte.id_compte = commentaire.id_compte WHERE  commentaire.id_commentaire = ?");
                    $req_com->bindValue(1, $c['id_commentaire']);
                    $req_com->execute();
                    $result = $req_com->fetch(PDO::FETCH_ASSOC);
                ?>
                <div id="comment_container"><b><?=$result["pseudo"]?>: </b> <?= $c['texte_commentaire']; ?></div></br>
            <?php }} ?>
            <?php
            if ($admin) echo "<input id='valider' type='submit' name='valider' value='Valider'></form><form id='form' action='./upload_recette.php' method='post'><input type='hidden' name='id' value='".$_GET['id']."'><input type='hidden' name='nom' value='".$title."'><input id='refuser' type='submit' name='refuser' value='Refuser'></form>";
            ?>
            <script src="../js/scriptIndex.js"></script>
            <?php 
            if (isset($_GET['err']))
                echo $_GET['err'];
            ?>

        </div>
    </main>
    <?php if (isset($_SESSION['mode']) && $_SESSION['mode'] == 1)
    echo '<script>
            document.getElementById(\'file\').onchange = function (e){
                var file = e.target.files[0];

                var reader = new FileReader();
                reader.onloadend = function(){
                    document.getElementById(\'modif_illu\').style.backgroundImage = "url(" + reader.result + ")";
                }
                if(file){
                    reader.readAsDataURL(file);
                }
            }
        </script>'; 
        
        
        ?>
</body>
</html>