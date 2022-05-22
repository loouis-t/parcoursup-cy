<?php 
    if (!isset($_SESSION['droits']) || $_SESSION['droits'] !== "Responsable admission") {
        header('Location: /accueil/accueil.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../root.css">
        <link rel="stylesheet" href="/accueil/logs/logs.css">
        <title>Accueil</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <h2>Modifier les attributions d'options</h2>
        <p>Ici vous pouvez modifier les attributions de l'algorithme quant au placement des élèves selon leurs choix d'options.</p>
    </body>
</html>