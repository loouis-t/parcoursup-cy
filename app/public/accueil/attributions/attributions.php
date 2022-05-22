<?php 
    if (!isset($_SESSION['droits']) || $_SESSION['droits'] !== "Responsable admission") {
        header('Location: /accueil/accueil.php');
        exit();
    }

    if (isset($_POST['attribuer']) && $_POST['attribuer'] === "true") {
        include('../../backend/api/matching.php');
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
        <h2>Charger les fichiers de données</h2>
        <p>Ajoutez ici les fichiers de données choix des étudiants selon le parcours.</p>
        <!-- ///// RETIRER CETTE NOTE ///// -->
        <p class="error"><u>Note (louis):</u> ajouter ici un form pour charger les fichiers 'choixEtudiantsParcours1.csv', ...</p>

        <h2>Attribution automatique d'option</h2>
        <p>Un alogrithme s'occupe d'attribuer les options de parcours aux élèves selon leurs choix, mais aussi selon le nombre de places disponibles.</p>
        <form action="/accueil/accueil.php?page=attributions" method="post">
            <input type="hidden" name="attribuer" value="true">
            <input class="button" type="submit" value="Attribuer les options">
            <?php 
                if (isset($_GET['state'])) {
                    switch ($_GET['state']) {
                        case "success":
                            echo '<p class="success">Les options ont été attribuées.</p>';
                            break;
                        case "err":
                            echo '<p class="error">Les options n\'ont pas pu être attribuées.</p>';
                    }
                }
            ?>
        </form>

        
        <h2>Modifier les attributions d'options</h2>
        <p>Ici vous pouvez modifier les attributions de l'algorithme quant au placement des élèves selon leurs choix d'options.</p>
    </body>
</html>