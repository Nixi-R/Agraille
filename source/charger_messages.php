<?php
    $bdd = new PDO(
        'mysql:host=localhost;dbname=agrailledb;charset=utf8;',
        'root',
        ''
    );
    $recupMessages = $bdd->query("SELECT * FROM messagerie");
    /*$req_com = $bdd->query("SELECT pseudo FROM compte INNER JOIN messagerie ON compte.id_compte = messagerie.id_compte WHERE messagerie.id_messagerie = " . $message['id_messagerie']);*/

    while($message = $recupMessages->fetch()/* && $pseudo = $req_com->fetch()*/){
        ?>
        <div class="message">
            <p><?= $message['date_messagerie'] . " " . $message['texte_messagerie']; ?></p>
        </div>
        <?php
    }
?>

