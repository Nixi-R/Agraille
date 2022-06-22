<?php
session_start();

if (isset($_SESSION['idCompte']))
    header('Location: ../');
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../css/inscription.css">
        <title>Agraille Inscription</title>
        <link rel="icon" href="../img/icone_agraille.png" sizes="any">
        
    </head>
    <body>
        <div id="upside">
            <img onclick="location.href='../'" id="agrailleImg" src="../img/logo_agraille.png">
        </div>
        <div id="squareSign">
            <p id="main">INSCRIPTION</p>
            <form id="form" enctype="multipart/form-data" method="POST" action="./certif_compte">
                <div id="pngD">
                    <h3 id="labelPP">Photo de Profil</h3>
                    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                    <input id="pngI" type="file" name="photo" accept="image/jpg, image/jpeg, image/png, image/gif">
                </div>
                <label id="labelPseudo">Pseudo :</label></br>
                <input id="pseudo" type="text" name="pseudo" value="<?php if (isset($_GET['pseudo'])) echo $_GET['pseudo']; ?>" minlength=4 maxlength=30 required></br>
                <label id="labelEmail">E-mail :</label></br>
                <input id="email" type="email" name="email" value="<?php if (isset($_GET['email'])) echo $_GET['email']; ?>" required></br>
                <label id="labelPass">Mot de passe :</label></br>
                <input id="password" type="password" name="password" value="<?php if (isset($_GET['password'])) echo $_GET['password']; ?>" minlength=4 maxlength=30 required></br>
                <label id="labelVerif">Vérification :</label></br>
                <input id="verif_password" type="password" name="verif_password" minlength=4 maxlength=30 required></br>
                <input id="signin" type="submit" value="S'inscrire">
                <p style="font-size:10px;"><?php if(isset($_GET['erreur'])) echo $_GET['erreur']; ?></p>
            </form>
        </div>
    </body>
    <script>
            document.getElementById('pngI').onchange = function (e){
                var file = e.target.files[0];

                var reader = new FileReader();
                reader.onloadend = function(){
                    document.getElementById('pngD').style.backgroundImage = "url(" + reader.result + ")";
                    document.getElementById('pngD').style.backgroundSize = "cover";
                    document.getElementById('labelPP').style.opacity = 0;
                }
                if(file){
                    reader.readAsDataURL(file);
                }
            }
        </script>
</html>