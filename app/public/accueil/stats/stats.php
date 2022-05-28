<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../root.css">
        <link rel="stylesheet" href="/accueil/stats/stats.css">
        <title>Accueil</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    </head>
    <body>
        <h2>Statistiques</h2>

        <?php

            if (file_exists("../../backend/db/placesFinales")) {

                // header
                echo '<table><tr><th></th>';
                $PARCOURS = [ "ACTU", "BI", "CS", "DS", "FT", "HPDA", "IAC", "IAP", "ICC", "INEM", "MMF", "VISUA" ];
                forEach($PARCOURS as $parcours) {
                    echo '<th>' . $parcours . '</th>';
                }

                // moyenne
                echo '<tr><td>Moyenne</td>';
                forEach($PARCOURS as $parcours) {
                    if (($handle = fopen("../../backend/db/placesFinales/" . $parcours . ".csv", "r")) !== FALSE) {
                        $counter = 0;
                        $sum = 0;
                        while (($line = fgetcsv($handle, 0, ",")) !== FALSE) {
                            $sum += $line[4];
                            $counter++;
                        }
                        fclose($handle);
                        echo '<td>' . round($sum / $counter, 2) . '</td>';
                    }
                }

                // moyenne rang
                echo '<tr><td>Moyenne rang</td>';
                forEach($PARCOURS as $parcours) {
                    if (($handle = fopen("../../backend/db/placesFinales/" . $parcours . ".csv", "r")) !== FALSE) {
                        $counter = 0;
                        $sum = 0;
                        while (($line = fgetcsv($handle, 0, ",")) !== FALSE) {
                            $rang = 5; // rang de départ (option 1)
                            while (isset($line[$rang])) {
                                if ($line[$rang] === $parcours) {
                                    $sum += $rang - 4;
                                    break;
                                }
                                $rang++;
                            }
                            $counter++;
                        }
                        fclose($handle);
                        echo '<td>' . round($sum / $counter, 2) . '</td>';
                    }
                }


                echo '</tr></table>';
            } else {
                echo "<p class='error'>Les options n'ont pas encore été attribuées.</p>";
            }

        ?>
    </body>
</html>