<?php 
    if (!isset($_SESSION['droits']) || $_SESSION['droits'] !== "admin") {
        header('Location: /accueil/accueil.php');
        exit();
    }


    if (isset($_FILES['file']) && $_FILES['file']['name'] !== "") {
        if ($_FILES['file']['error'] == 0 && explode('.', $_FILES['file']['name'])[1] == "csv") {
            $filename = 'students_to_add_temp.csv';

            // Vérifie si le fichier existe avant de le télécharger.
            if(file_exists($filename)){
                move_uploaded_file($_FILES["file"]["tmp_name"], $filename.".tmp");
                rename($filename.".tmp", $filename);
            } else{
                move_uploaded_file($_FILES["file"]["tmp_name"], $filename);
            }

            if(($handle = fopen($filename, "r")) !== FALSE) {
                // set separator to comma or semicolon
                if (($data = fgetcsv($handle, 1000, ",")) !== FALSE && isset($data[1])) {
                    $sep = ",";
                } else {
                    $sep = ";";
                }
                // create students
                $counter = 0;
                while (($data = fgetcsv($handle, 1000, $sep)) !== FALSE) {
                    $mail_already_exists = false;

                    // check if student already exists
                    if(($handle_ids = fopen("../../backend/db/identifiants.csv", "r")) !== FALSE) {
                        while (($existing_mail = fgetcsv($handle_ids, 1000, ',')) !== FALSE) {
                            if ($existing_mail[2] === $data[2]) {
                                $mail_already_exists = true;
                                break;
                            }
                        }
                        fclose($handle_ids);
                    }

                    $fp = fopen("../../backend/db/identifiants.csv", "a+");
                    if (!$mail_already_exists) {
                        // add student
                        fputcsv($fp, [ $data[0], $data[1], $data[2], hash('sha256', 'admin'), 'eleve' ]);
                        $counter++;
                    }
                    fclose($fp);    // close file
                }
                fclose($handle);    // close csv file
                unlink($filename);  // supprime le fichier temporaire

                // log : inscription en masse
                if ($counter > 0) {
                    $logs = fopen("../../backend/db/logs.csv", "a+");
                    fputcsv($logs, [ date('Y-m-d'), date('H:i:s'), "Inscription en masse de " . $counter . " élèves" ]);
                    fclose($logs);

                    header('Location: /accueil/accueil.php?page=inscriptions&err=false');
                    exit();
                } else {
                    header('Location: /accueil/accueil.php?page=inscriptions&err=true&err_msg=0');
                    exit();
                }

            }
        } else {
            header('Location: /accueil/accueil.php?page=inscriptions&err=true');
            exit();
        }
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../root.css">
        <link rel="stylesheet" href="/accueil/inscriptions/inscriptions.css">
        <title>Accueil</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <h2>Inscrire des élèves en masse</h2>
        <p>
            Cette page vous permet d'inscrire des listes d'éleves depuis un fichier csv. <br>
            Le fichier doit être séparé par "," ou ";" et doit contenir les colonnes suivantes : <i>prénom</i>, <i>nom</i>, <i>mail</i>.
        </p>

        <form action="/accueil/accueil.php?page=inscriptions" method="post" enctype="multipart/form-data">
            <label for="file">
                Importer un fichier csv :
                <input type="file" name="file" id="file" accept=".csv">
            </label>

            <?php
                if (isset($_GET['err'])) {
                    if ($_GET['err'] == "true") {
                        if(isset($_GET['err_msg']) && $_GET['err_msg'] == "0") {
                            echo '<p class="success">Aucun nouvel élève à ajouter.</p>';
                        } else {
                            echo '<p class="error">Erreur lors du téléchargement du fichier.</p>';
                        }
                    } else {
                        echo '<p class="success">Ajout effectué avec succès.</p>';
                    }
                }
            ?>

            <input class="button" type="submit" value="Envoyer">
        </form>
    </body>
</html>