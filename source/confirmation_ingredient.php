<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agraille|Rédiger votre recette !</title>
</head>
<body>
    <h2>Combien d'ingredient y'a t'il dans votre recette</h2>
    <form action="./redaction_recette" method="GET">
        <input name="ingredient_nombre" type="number" min="1" max="15"required/>
        <input type="submit" placeholder="valider">
    </form>

    <a href="../index">retour</a>
</body>
</html>