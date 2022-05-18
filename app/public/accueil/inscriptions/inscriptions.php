<?php 
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
                $fp = fopen("../../backend/db/identifiants.csv", "a+");

                // set separator to comma or semicolon
                if (($data = fgetcsv($handle, 1000, ",")) !== FALSE && isset($data[1])) {
                    $sep = ",";
                } else {
                    $sep = ";";
                }
                // create students
                while (($data = fgetcsv($handle, 1000, $sep)) !== FALSE) {

                    // check if student already exists
                    while (($existing_mail = fgetcsv($handle, 1000, ',')) !== FALSE) {
                        if ($existing_mail[2] === $data[2]) {
                            $mail_already_exists = true;
                            break;
                        }
                    }
                    if (!$mail_already_exists) {
                        fputcsv($fp, [
                            $data[0],
                            $data[1],
                            $data[2],
                            hash('sha256', 'admin'),
                            'eleve'
                        ]);
                    }
                }
                fclose($fp);
                fclose($handle);
            }
        } else {
            header('Location: /accueil/accueil.php?err=true&type=file');
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
        <p>Cette page vous permet d'inscrire des listes d'éleves depuis un fichier csv.</p>

        <form action="/accueil/accueil.php?page=inscriptions" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="file" accept=".csv">

            <input type="submit" value="Envoyer">
        </form>
    </body>
</html>