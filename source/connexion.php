<?php

session_start();

if (isset($_SESSION['idCompte']))
    header('Location: ./profil');

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../css/connexion.css">
        <title>Agraille Connexion</title>
        <link rel="icon" href="../img/icone_agraille.png" sizes="any">
    </head>
    <body>
        <div id="upside">
            <img onclick="location.href='../'" id="agrailleImg" src="../img/logo_agraille.png">
        </div>
        <div id="squareSign">
            <p id="main">CONNEXION</p>
            <form id="form" method="POST" action="loading_login">
                <label id="labelPseudo">Pseudo/Email :</label></br>
                <input id="pseudo" type="text" name="pseudo"></br>
                <label id="labelPass">Mot de passe :</label></br>
                <input id="password" type="password" name="password"></br>
                <input id="login" type="submit" value="Se connecter">
                <p id="or">Ou</p>
                <input id="signin" type="submit" formaction="inscription" value="S'inscrire">
                <p><?php if(isset($_GET['erreur'])) echo $_GET['erreur']; ?></p>
            </form>
        </div>
    </body>
</html>
