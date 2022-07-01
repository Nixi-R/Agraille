<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/icone_agraille.png" sizes="any">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <!-- <link rel="stylesheet" href="../css/profil.css"> -->
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