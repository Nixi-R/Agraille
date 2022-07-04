<?php
    $bdd = new PDO(
        'mysql:host=localhost;dbname=agrailledb;charset=utf8;',
        'root',
        ''
    );
    $recupMessages = $bdd->query("SELECT * FROM messagerie");
    while($message = $recupMessages->fetch()){
        ?>
        <div class="message">
            <h4><?= $message['pseudo']; ?></h4>
            <p><?= $message['message']; ?></p>
        </div>
        <?php
    }
?>

