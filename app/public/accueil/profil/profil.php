<?php
    // si l'utilisateur a cliqué sur enregistrer
    if (
        isset($_POST['mail'])
        && isset($_POST['password'])
        && isset($_POST['adresse'])
        && (
            strtolower($_POST['mail']) !== strtolower($_SESSION['mail'])
            || $_POST['password'] !== $_SESSION['password']
            || $_POST['adresse'] !== $_SESSION['adresse']
        )
    ) {
        // changer mail élève
        // si user veut modifier son mail, on vérifie que le mail n'est pas déjà utilisé
        if(
            strtolower($_POST['mail']) !== strtolower($_SESSION['mail'])
            && ($handle = fopen("../../backend/db/identifiants.csv", "r")) !== FALSE
        ) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($data[2] === $_POST['mail']) {
                    fclose($handle);
                        header('Location: /accueil/accueil.php?err=true&type=mail');
                        exit();
                    }
                }
        }
        // on change le mail (et l'adresse à y être)
        if(($handle = fopen("../../backend/db/identifiants.csv", "r")) !== FALSE) {
            $fp = fopen("../../backend/db/identifiants_temp.csv", "w");
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (strtolower($data[2]) !== strtolower($_SESSION['mail'])) {                   // si la ligne ne correspond pas a celle qu'on cherche
                    fputcsv($fp, $data);                                // ecrit les données dans le fichier temporaire
                } else {
                    // mail
                    $data[2] = strtolower($_POST['mail']);              // change le mail de l'élève
                    // adresse
                    if ($_POST['adresse'] !== 'inconnue') {
                        if (isset($data[5])) {
                            $data[5] = $_POST['adresse'];               // change adresse élève
                        } else {
                            array_push($data, $_POST['adresse']);       // ajoute l'adresse de l'eleve
                        }
                    }
                    // mot de passe
                    if ($_POST['password'] !== 't\'as cru mdr') {
                        $data[3] = hash('sha256', $_POST['password']);  // change le mot de passe de l'élève
                    }
                    fputcsv($fp, $data);                                // rajouter la ligne modifiée
                }
            }
            fclose($fp);
            fclose($handle);

            rename("../../backend/db/identifiants_temp.csv", "../../backend/db/identifiants.csv");

            $_SESSION['mail'] = strtolower($_POST['mail']);
            $_SESSION['adresse'] = $_POST['adresse'];
        }
    }

    // changer pdp
    if (isset($_FILES['pdp']) && $_FILES['pdp']['name'] !== "") {
        if ($_FILES['pdp']['error'] == 0) {
            $allowed = array(
                "jpg" => "image/jpg", 
                "jpeg" => "image/jpeg",
                "png" => "image/png"
            );

            $filename = $_SESSION['mail'].".jpg";
            if (isset($_FILES['pdp']['size'])) {
                $filesize = $_FILES['pdp']['size'];
            } else {
                $filesize = 0;
            }

            // vérifier si extension  valide + taille du fichier
            $ext = pathinfo(strtolower($_FILES['pdp']['name']), PATHINFO_EXTENSION);
            $maxsize = 5 * 1024 * 1024;
            if(!array_key_exists($ext, $allowed) || $filesize > $maxsize) { 
                header('Location: /accueil/accueil.php?err=true&type=pdp_size');
                exit();
            }

            // Vérifie si le fichier existe avant de le télécharger.
            if(file_exists("../assets/pdps/".$filename)){
                move_uploaded_file($_FILES["pdp"]["tmp_name"], "../assets/pdps/".$filename.".tmp");
                rename("../assets/pdps/".$filename.".tmp", "../assets/pdps/".$filename);
            } else{
                move_uploaded_file($_FILES["pdp"]["tmp_name"], "../assets/pdps/".$filename);
            }
        } else {
            header('Location: /accueil/accueil.php?err=true&type=pdp');
            exit();
        }

        header('Location: /accueil/accueil.php?err=false&type=success');
        exit();

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
            <img class="profil__pdp" src=
            "
            <?php
                echo "../../assets/pdps/".$_SESSION['mail'].".jpg";
            ?>
            " alt="pdp__currentUser" onerror="this.src='../../assets/pdps/pdp__inconnue.jpg'">
            <div class="profil__intro">
                <h2>Bienvenue <i><?php echo $_SESSION['prenom']; ?></i></h2>
                <p><?php echo $_SESSION['droits']; ?></p>
            </div>

            <h3>Mes infos :</h3>
            <form action="/accueil/accueil.php" method="post" enctype="multipart/form-data">
                <label>
                    Adresse mail : <input type="email" name="mail" value="<?php echo $_SESSION['mail'];?>">
                </label>
                <label>
                    Mot de passe : <input type="password" name="password" value="t'as cru mdr">
                </label>
                <label>
                    Photo de profil : <u>changer</u>
                    <input class="pdp-uploader" name="pdp" type="file" accept="image/jpg, image/png, image/jpeg">
                </label>
                <label>
                    Adresse postale : <input type="text" name="adresse" value="<?php
                        echo isset($_SESSION['adresse']) ? $_SESSION['adresse'] : "inconnue";
                    ?>">
                </label>

                <?php
                    if (isset($_GET['err']) && isset($_GET['type'])) {
                        switch ($_GET['type']) {
                            case "mail":
                                echo '<p class="error">Cette adresse mail n\'est pas disponible.</p>';
                                break;
                                case "pdp":
                                    echo '<p class="error">Impossible d\'uploader cette photo.</p>';
                                    break;
                                case "pdp_size":
                                    echo '<p class="error">Veuillez importer une image inférieure à 5mo.</p>';
                                    break;
                                case "success":
                                    echo '<p class="success">Modifications effectuées avec succès.</p>';
                                    break;
                            default:
                                break;
                        }
                    }
                ?>

                <input class="button" type="submit" value="Enregistrer">
            </form>
        </div>
    </body>
</html>
