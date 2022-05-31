<?php  
    session_start();
    $bdd = new PDO ('mysql:host=localhost;dbname=agrailledb;charset=utf8','root','', [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1 COLLATE latin1_general_ci",
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $id = $_SESSION["idCompte"];
    $req = $bdd->prepare("DELETE FROM compte WHERE id=$id" );
    $req->execute();
    session_unset();
    header('Location: ../');
?>