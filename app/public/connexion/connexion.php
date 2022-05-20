<?php
    session_start();                    // démarrer session
    if (isset($_SESSION['prenom'])) {
        header('Location: /accueil/accueil.php');
        exit();
    }

    if (
        isset($_POST['mail'])
        && isset($_POST['pw'])
    ) {
        if (
            $_POST['mail'] == 'dvd'
            && $_POST['pw'] == 'dvd'
        ) {
            header('Location: https://www.puvad.fr');
            exit();
        } else {
            if(($handle = fopen("../../backend/db/identifiants.csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    for ($i=0; $i<count($data); $i++) {
                        if (
                            strtolower(str_replace(' ', '', $_POST['mail'])) == strtolower($data[2])
                            && hash('sha256', $_POST['pw']) == $data[3]
                        ) {
                            $_SESSION['prenom'] = $data[0];     // stocker prenom dans session
                            $_SESSION['nom'] = $data[1];        // stocker nom dans session
                            $_SESSION['mail'] = $_POST['mail']; // cookie mail
                            $_SESSION['droits'] = $data[4];     // cookie droits (eleve/prof/admin)
                            fclose($handle);                    // libérer csv
                            header('Location: /accueil/accueil.php');
                            exit();
                        }
                    }
                }
                fclose($handle);
                header('Location: /connexion/connexion.php?err=true');
                exit();
            }
        } 
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="connexion.css">
        <link rel="stylesheet" href="../root.css">
        <script type="text/javascript" src="../index.js" defer></script>
        <title>Connexion</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="/"><h1>parcoursup-eisti</h1></a>
            <div>
                <label class="switch"><input class="checkbox" type="checkbox"><span class="slider round" onclick="dark()"></span></label>
            </div>
        </header>
            <article>
            <div class="box">
                <h2>Connexion</h2>

                <form action="connexion.php" method="post">
                    <input type="mail" name="mail" placeholder="e-mail" required>
                    <input type="password" name="pw" placeholder="Mot de passe" required>

                    <?php
                        if(isset($_GET['err']) && $_GET['err'] == "true") {
                            echo '<p class="error">Identifiant ou mot de passe incorrect</p>';
                        }
                    ?>
                    <input class="button" type="submit" value="Se connecter">
                </form>

                <p>Pas encore de compte? <a href="../inscription/inscription.php">Inscrivez vous</a>!</p>
            </div>
        </article>
    </body>
</html>
