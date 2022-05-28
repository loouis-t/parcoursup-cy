<?php
    // redirection si on est pas responsable admission
    if (!isset($_SESSION['droits']) || $_SESSION['droits'] !== "Responsable admission") {
        header('Location: /accueil/accueil.php');
        exit();
    }

    // lancer le matching si on a cliqué sur le bouton
    if (isset($_POST['attribuer']) && $_POST['attribuer'] === "true") {
        include('../../backend/api/matching.php');
    }


    if (
        (isset($_FILES['parcours1']) && $_FILES['parcours1']['name'] !== "")
        || (isset($_FILES['parcours2']) && $_FILES['parcours2']['name'] !== "")
        || (isset($_FILES['parcours3']) && $_FILES['parcours3']['name'] !== "")
    ) {
        if (
            ($_FILES['parcours1']['error'] == 0 && explode('.', $_FILES['parcours1']['name'])[1] == "csv")
            || ($_FILES['parcours2']['error'] == 0 && explode('.', $_FILES['parcours2']['name'])[1] == "csv")
            || ($_FILES['parcours3']['error'] == 0 && explode('.', $_FILES['parcours3']['name'])[1] == "csv")
        ) {
            $counter = 0;
            $path = "../../backend/db/";
            $filenames = [ 
                'choixEtudiantsParcours1.csv', 
                'choixEtudiantsParcours2.csv', 
                'choixEtudiantsParcours3.csv'
            ];

            // uploader le fichier
            for ($i=1; $i<=3; $i++) {
                if (
                    !($_FILES['parcours' . $i]['error'] !== 0)
                    && !($_FILES['parcours' . $i]['name'] === "")
                ) { // si le fichier a été uploadé par l'admission
                    // si un fichier du meme nom existe déjà, on l'overwrite
                    $current_filename = $path . $filenames[$i-1];
                    if(file_exists($current_filename)){
                        move_uploaded_file($_FILES["parcours" . $i]["tmp_name"], $current_filename.".tmp");
                        rename($current_filename.".tmp", $current_filename); // overwrite le fichier existant
                    } else{
                        // sinon on l'ajoute juste
                        move_uploaded_file($_FILES["parcours" . $i]["tmp_name"], $current_filename);
                    }
                    $counter++;
                }
            }

            // log : ajout fichier choixEtudiantsParcoursN.csv
            if ($counter > 0) {
                $logs = fopen("../../backend/db/logs.csv", "a+");
                fputcsv($logs, [ date('Y-m-d'), date('H:i:s'), htmlspecialchars("Admission : upload de " . $counter . " fichier(s) de choix d'étudiants.") ]);
                fclose($logs);

                header('Location: /accueil/accueil.php?page=attributions&err=false');
                exit();
            }
        } else {
            header('Location: /accueil/accueil.php?page=attributions&err=true');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../root.css">
        <link rel="stylesheet" href="/accueil/attributions/attributions.css">
        <script src="/accueil/attributions/attributions.js" defer></script>
        <title>Accueil</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <h2>Charger les fichiers de données</h2>

        <p>Ajoutez ici les fichiers de données choix des étudiants selon le parcours.</p>
        <form action="/accueil/accueil.php?page=attributions" method="post" enctype="multipart/form-data">
            <label for="parcours1">
                Importer un fichier csv (parcours 1) &nbsp;: 
                <input type="file" name="parcours1" id="file" accept=".csv">
            </label>
            <label for="parcours2">
                Importer un fichier csv (parcours 2) : 
                <input type="file" name="parcours2" id="file" accept=".csv">
            </label>
            <label for="parcours3">
                Importer un fichier csv (parcours 3) : 
                <input type="file" name="parcours3" id="file" accept=".csv">
            </label>

            <?php
                if (isset($_GET['err'])) {
                    if ($_GET['err'] == "true") {
                        echo '<p class="error">Erreur lors du téléchargement du fichier.</p>';
                    } else {
                        echo '<p class="success">Ajout effectué avec succès.</p>';
                    }
                }
            ?>

            <input class="button" type="submit" value="Envoyer">
        </form>

        <h2>Attribution automatique d'option</h2>

        <p>Un alogrithme s'occupe d'attribuer les options de parcours aux élèves selon leurs choix, mais aussi selon le nombre de places disponibles.</p>
        <form action="/accueil/accueil.php?page=attributions" method="post">
            <input type="hidden" name="attribuer" value="true">
            <input class="button" type="submit" value="Attribuer les options">
            <?php 
                if (isset($_GET['state'])) {
                    switch ($_GET['state']) {
                        case "success":
                            echo '<p class="success">Les options ont été attribuées.</p>';
                            break;
                        case "err":
                            echo '<p class="error">Les options n\'ont pas pu être attribuées.</p>';
                    }
                }
            ?>
        </form>

        <h2>Modifier les attributions d'options</h2>

        <p>
            Ici vous pouvez modifier les attributions de l'algorithme quant au placement des élèves selon leurs choix d'options. <br>
            Cherchez l'élève souhaité à l'aide du champ de recherche ci-dessous, puis cliquez sur l'option que vous souhaitez attribuer dans la dernière colonne du tableau.
        </p>

        <div class="recherche">
            <input type="email" placeholder="Adresse mail" onkeydown="entrer(this, event)">
            <input type="button" class="button" value="Chercher" onclick="chercher(this, event)">
        </div>

        <?php

            $PARCOURS = [ "ACTU", "BI", "CS", "DS", "FT", "HPDA", "IAC", "IAP", "ICC", "INEM", "MMF", "VISUA" ];
            if (file_exists('../../backend/db/placesFinales/')) {
                // en tete tableau uniquement si les choix ont été attribués
                echo '<table><tr>
                                <th>Prénom</th>
                                <th>Nom</th>
                                <th>Mail</th>
                                <th>ECTS</th>
                                <th>Moyenne</th>
                                <th>Option attribuée</th>
                                <th>Autres options demandées</th>
                            </tr>';
            }
            
            foreach ($PARCOURS as $parcours) {
                if(($handle = fopen("../../backend/db/placesFinales/" . $parcours . ".csv", "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                        echo '<tr>';
                        echo '<td>' . $data[0] . '</td>';                                   // Prénom élève
                        echo '<td>' . $data[1] . '</td>';                                   // Nom élève
                        echo '<td id="' . strtolower($data[2]) . '">' . $data[2] . '</td>'; // Mail élève
                        echo '<td>' . $data[3] . '</td>';                                   // ECTS élève
                        echo '<td>' . $data[4] . '</td>';                                   // Moyenne élève
                        echo '<td>' . $parcours . '</td><td>';                              // Choix attribué
                        $i = 5;
                        while (isset($data[$i])) {
                            $choix = "<a onclick=\"modifier_option(this, '$data[$i]', '$parcours', '$data[2]')\">" . str_replace(' ', '', $data[$i]) . "</a>";
                            if (str_replace(' ', '', $data[$i]) !== $parcours) {                          // Si l'option n'est pas le choix attribué
                                echo (isset($data[$i+1]) && str_replace(' ', '', $data[$i+1]) !== $parcours) ? $choix . ', ' : $choix;   // pas de virgule à la fin
                            }
                            $i++;
                        }
                        
                        echo '</td></tr>';
                    }
                    fclose($handle);
                } else {
                    // si les choix n'ont pas été attribués
                    echo '<p class="error">Les choix n\'ont pas été attribués.</p>';
                    break;
                }
            }
        ?>
        </table>
    </body>
</html>