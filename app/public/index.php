<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="root.css">
        <link rel="stylesheet" href="index.css">
        <!-- Récup favicon sur site cy-tech -->
        <link rel="icon" type="image/png" href="https://cytech.cyu.fr/jsp/images/favicon.png" />
        <title>Parcoursup EISTI</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
        <script type="text/javascript" src="index.js" defer></script>
    </head>
    <body class="">
        <header>
            <a href="/"><h1>parcoursup-eisti</h1></a>
            <div>
                <a href="connexion/connexion.php" class="button">Connexion</a>
                <a href="inscription/inscription.php" class="button">Inscription</a>
                <label class="switch"><input class="checkbox" type="checkbox"><span class="slider round" onclick="dark()"></span></label>
            </div>
        </header>

        <article>
            <div class="left">
                <h2>Bienvenue sur la plateforme de gestion de vos choix d'options</h2>
                <p>Choisissez l'option de vos rêves pour votre dernière année à CY-Tech</p>
            </div>

            <img src="./assets/cy-tech.png" alt="">

            <div class="wave"></div>
        </article>
        <?php include('footer.php'); ?>
    </body>
</html>
