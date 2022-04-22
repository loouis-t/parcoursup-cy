<?php
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
        $already_in_database = false;
        if(($handle = fopen("../../backend/db/identifiants.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                for ($i=0; $i<count($data); $i++) {
                    if (
                        str_replace(' ', '', $_POST['prenom'])
                        && str_replace(' ', '', $_POST['nom'])
                        && str_replace(' ', '', $_POST['mail'])
                        && $data[3] ==  hash('sha256', $_POST['pw'])
                    ) {
                        $already_in_database = true;
                        break;
                    }
                }
            }
            fclose($handle);
        }

        // si il n'y est pas => on le rajoute
        if(!$already_in_database) {
            $fp = fopen("../../backend/db/identifiants.csv", "a+");
            fputcsv($fp, $current_eleve);
    
            session_start();
            $_SESSION['mail'] = $_POST['mail'];
            $_SESSION['droits'] = $_POST['eleve'];
    
            fclose($fp);                                    // Fermeture du fichier
            header('Location: /accueil/accueil.php');       // redirection
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="inscription.css">
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
                    if($already_in_database) {
                        echo '<p class="error">Cet utilisateur existe déjà!</p>';
                    }
                ?>
                <input class="button" type="submit" value="S'inscrire">
            </form>

            <p>Vous avez déjà un compte? <a href="../inscription/inscription.php">Connectez vous</a>!</p>
        </article>
    </body>
</html>