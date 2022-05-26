<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../root.css">
        <link rel="stylesheet" href="/accueil/logs/logs.css">
        <title>Accueil</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <h2>Logs système</h2>

        <table>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Message</th>
            </tr>
            <?php
                if(($handle = fopen("../../backend/db/logs.csv", "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                        echo '<tr>';
                        echo '<td>' . $data[0] . '</td>';
                        echo '<td>' . $data[1] . '</td>';
                        echo '<td>' . $data[2] . '</td>';
                        echo '</tr>';
                    }
                }
                fclose($handle);
            ?>
        </table>

        <br>
        <h2>Logs signalements</h2>

        <table>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Signaleur</th>
                <th>Signalé</th>
                <th>Message signalé</th>
            </tr>
            <?php
                if(($handle = fopen("../../backend/db/signalements.csv", "r")) !== FALSE) {
                    fgetcsv($handle, 1000, ","); // ignore la première ligne
                    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                        echo '<tr>';
                        echo '<td>' . $data[0] . '</td>';
                        echo '<td>' . $data[1] . '</td>';
                        echo '<td>' . $data[3] . '</td>';
                        echo '<td>' . $data[2] . '</td>';
                        echo '<td>' . $data[4] . '</td>';
                        echo '</tr>';
                    }
                }
                fclose($handle);
            ?>
        </table>

        <br>
        <h2>Logs blocages</h2>

        <table>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Bloqueur</th>
                <th>Bloqué</th>
                <th>Motif</th>
            </tr>
            <?php
                if(($handle = fopen("../../backend/db/utilisateurs_bloques.csv", "r")) !== FALSE) {
                    fgetcsv($handle, 1000, ','); // ignore la première ligne
                    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                        echo '<tr>';
                        echo '<td>' . $data[0] . '</td>';
                        echo '<td>' . $data[1] . '</td>';
                        echo '<td>' . $data[2] . '</td>';
                        echo '<td>' . $data[3] . '</td>';
                        echo '<td>' . $data[4] . '</td>';
                        echo '</tr>';
                    }
                }
                fclose($handle);
            ?>
        </table>
    </body>
</html>