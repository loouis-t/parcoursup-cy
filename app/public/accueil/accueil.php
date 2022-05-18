<?php
    session_start();
    if (!isset($_SESSION['prenom']) || isset($_GET['deco'])) {
        session_destroy();
        header('Location: /');
        exit();
    }

    $_SERVER['DOCUMENT_ROOT'] = '/parcoursup-eisti/app/backend/assets';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../root.css">
        <link rel="stylesheet" href="accueil.css">
        <script type="text/javascript" src="../index.js" defer></script>
        <title>Accueil</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="/"><h1>parcoursup-eisti</h1></a>
            <div>
                <a class="button" href="/accueil/accueil.php?&deco=true">Déconnexion</a>
            </div>
        </header>
        <article>
            <nav>
                <a class="button" href="/accueil/accueil.php?page=profil">Mon profil</a>
                
                <?php
                switch ($_SESSION['droits']) {
                    case "admin":
                        break;
                    case "prof":
                        break;
                    case "eleve":
                        echo '<a class="button" href="/accueil/accueil.php?page=moyenne">Ma scolarité</a>';
                        break;
                }
                ?>
            </nav>

            <section class="hero">
                <?php
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                        if ($page == 'profil') {
                            include 'profil/profil.php';
                        } else if ($page == 'moyenne') {
                            include 'moyenne/moyenne.php';
                        } else if ($page == 'ects') {
                            include 'ects/ects.php';
                        } else if ($page == 'choix') {
                            include 'choix/choix.php';
                        } else {
                            include 'profil/profil.php';
                        }
                    } else {
                        include 'profil/profil.php';
                    }
                ?>
            </section>
        </article>
    </body>
</html>
