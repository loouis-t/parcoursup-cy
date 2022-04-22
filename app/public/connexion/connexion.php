<?php
    if (
        isset($_POST['mail'])
        && isset($_POST['pw'])
    ) {
        if(($handle = fopen("../../backend/db/identifiants.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                for ($i=0; $i<count($data); $i++) {
                    if (
                        $data[2] == $_POST['mail'] 
                        && $data[3] ==  hash('sha256', $_POST['pw'])
                    ) {
                        session_start();                    // démarrer session
                        $_SESSION['mail'] = $_POST['mail']; // cookie mail
                        $_SESSION['droits'] = $data[4];     // cookie droits (eleve/prof/admin)
                        fclose($handle);                    // libérer csv
                        header('Location: /accueil/accueil.php');
                    }
                }
            }
            fclose($handle);
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="connexion.css">
        <title>Connexion</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <article>
            <h2>Connexion</h2>

            <form action="connexion.php" method="post">
                <input type="mail" name="mail" placeholder="e-mail" required>
                <input type="password" name="pw" placeholder="Mot de passe" required>

                <input class="button" type="submit" value="Se connecter">
            </form>

            <p>Pas encore de compte? <a href="../inscription/inscription.php">Inscrivez vous</a>!</p>
        </article>
    </body>
</html>