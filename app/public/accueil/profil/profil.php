<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../root.css">
        <link rel="stylesheet" href="/accueil/profil/profil.css">
        <title>Accueil</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <div class="profil">
            <div class="profil__pdp"></div>
            <div class="profil__intro">
                <h2>Bienvenue <i><?php echo $_SESSION['prenom']; ?></i></h2>
                <p><?php echo $_SESSION['droits']; ?></p>
            </div>

            <h3>Mes infos :</h3>
            <form action="accueil/profil/profil.php" method="post">
                <p>
                    Adresse mail : <input type="email" name="mail" value="<?php echo $_SESSION['mail'];?>">
                </p>
                <p>
                    Photo de profil : <input type="file">
                </p>
                <p>
                    Adresse postale : <input type="text" name="adresse" value="<?php
                        if(isset($_SESSION['adresse'])) {
                            echo $_SESSION['adresse'];
                        } else {
                            echo "inconnue";
                        }
                    ?>">
                </p>

                <input type="submit" value="Enregistrer">
            </form>
        </div>
    </body>
</html>