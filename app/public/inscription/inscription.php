<?php
    date_default_timezone_set('Europe/Paris');
    
    session_start();                    // démarrer session
    if (isset($_SESSION['mail'])) {
        header('Location: /accueil/accueil.php');
        exit();
    }

    if (
        isset($_POST['prenom'])
        && isset($_POST['nom'])
        && isset($_POST['mail'])
        && isset($_POST['pw'])
    ) {
        $current_eleve = [
            str_replace(' ', '', $_POST['prenom']),
            str_replace(' ', '', $_POST['nom']),
            str_replace(' ', '', $_POST['mail']),
            hash('sha256', $_POST['pw']),
            'eleve'
        ];


        // Vérifier si le boug est déja dans le csv
        if(($handle = fopen("../../backend/db/identifiants.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                for ($i=0; $i<count($data); $i++) {
                    if (
                        strtolower(str_replace(' ', '', $_POST['mail'])) == strtolower($data[2])
                    ) {
                        fclose($handle);
                        header('Location: /inscription/inscription.php?err=true');
                        exit();
                    }
                }
            }
            fclose($handle);
        }

        // si il n'y est pas => on le rajoute
        if($_GET['err'] !== "true") {
            $fp = fopen("../../backend/db/identifiants.csv", "a+");
            fputcsv($fp, $current_eleve);

            $_SESSION['prenom'] = $current_eleve[0];
            $_SESSION['nom'] = $current_eleve[1];
            $_SESSION['mail'] = strtolower($_POST['mail']);
            $_SESSION['droits'] = 'eleve';

            fclose($fp);                                    // Fermeture 'identifiants.csv'

            // Log inscription
            $logs = fopen("../../backend/db/logs.csv", "a+");
            fputcsv($logs, [ date('Y-m-d'), date('H:i:s'), "Inscription : " . strtolower($_SESSION['mail']) ]);
            fclose($logs);                                  // Fermeture 'logs.csv'

            header('Location: /accueil/accueil.php');       // redirection
            exit();
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../root.css">
        <link rel="stylesheet" href="inscription.css">
        <script type="text/javascript" src="../index.js" defer></script>
        <title>inscription</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    </head>
    <body>
        <article>
            <h2>Inscription</h2>

            <form action="inscription.php" method="post">
                <input type="text" name="prenom" placeholder="Prénom" required>
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="mail" name="mail" placeholder="e-mail" required>
                <input type="password" name="pw" placeholder="Mot de passe" required>

                <?php
                    if (isset($_GET['err']) && $_GET['err'] === "true") {
                        echo '<p class="error"">Cet utilisateur existe déjà!</p>';
                    }
                ?>

                <input class="button" type="submit" value="S'inscrire">
            </form>

            <p>Vous avez déjà un compte? <a href="../connexion/connexion.php">Connectez vous</a>!</p>
        </article>
    </body>
</html>
