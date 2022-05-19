<?php
    date_default_timezone_set('Europe/Paris');
    
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
                        echo '<a class="button" href="/accueil/accueil.php?page=inscriptions">Inscrire élèves</a>';
                        echo '<a class="button" href="/accueil/accueil.php?page=logs">Logs</a>';
                        break;
                    case "prof":
                        break;
                    case "eleve":
                        echo '<a class="button" href="/accueil/accueil.php?page=scolarite">Ma scolarité</a>';
                        break;
                }
                ?>

                <a class="button" href="/accueil/accueil.php?page=messagerie">Messagerie</a>
            </nav>

            <section class="hero">
                <?php
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];

                        switch ($page) {
                            case "profil":
                                include 'profil/profil.php';
                                break;
                            case "scolarite":
                                include 'scolarite/scolarite.php';
                                break;
                            case "logs":
                                include 'logs/logs.php';
                                break;
                            case "messagerie":
                                include 'messagerie/messagerie.php';
                                break;
                            case "inscriptions":
                                include 'inscriptions/inscriptions.php';
                                break;
                            default:
                                include 'profil/profil.php';
                                break;
                        }
                    } else {
                        include 'profil/profil.php';
                    }
                ?>
            </section>
        </article>
    </body>
</html>
