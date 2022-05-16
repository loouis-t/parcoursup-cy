<?php
    // si l'utilisateur a cliqué sur enregistrer
    if (isset($_POST['mail']) && isset($_POST['adresse'])) {
        if(($handle = fopen("../../backend/db/identifiants.csv", "r")) !== FALSE) {
            $fp = fopen("../../backend/db/identifiants_temp.csv", "w");
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($data[2] !== $_SESSION['mail']) {
                    fputcsv($fp, $data);    // ecrit les données dans le fichier temporaire
                } else {
                    $data[2] = strtolower($_POST['mail']);           // change le mail de l'élève
                    if (isset($_POST['adresse']) && $_POST['adresse'] != 'inconnue') {
                        if (isset($data[5])) {
                            $data[5] = $_POST['adresse'];
                        } else {
                            array_push($data, $_POST['adresse']); // ajoute l'adresse de l'eleve
                        }
                    }
                    fputcsv($fp, $data);   // rajouter la ligne modifiée
                }
            }
            fclose($fp);
            fclose($handle);
            
            rename("../../backend/db/identifiants_temp.csv", "../../backend/db/identifiants.csv");
    
            $_SESSION['mail'] = strtolower($_POST['mail']);
            $_SESSION['adresse'] = $_POST['adresse'];
        }
    }
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
            <img class="profil__pdp" src="<?php echo '../../assets/pdps/pdp__'.$_SESSION['mail'].'.jpg'; ?>" alt="pdp__currentUser">
            <div class="profil__intro">
                <h2>Bienvenue <i><?php echo $_SESSION['prenom']; ?></i></h2>
                <p><?php echo $_SESSION['droits']; ?></p>
            </div>

            <h3>Mes infos :</h3>
            <form action="/accueil/accueil.php" method="post">
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