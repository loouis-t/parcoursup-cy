<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../root.css">
        <link rel="stylesheet" href="/accueil/scolarite/scolarite.css">
        <title>Accueil</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <h2>Ma scolarité</h2>

        <?php
            $choix = [];
            $ects; $moyenne;
            $user_found = false;

            for($i=1; $i<=3; $i++) {
                if(!$user_found && ($handle = fopen("../../backend/db/choixEtudiantsParcours".$i.".csv", "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                        if ($data[2] == $_SESSION['mail']) {
                            $user_found = true; // ne pas réiterer la boucle for

                            $ects = $data[3];
                            $moyenne = $data[4];

                            echo "<p><u>Moyenne :</u> " . $moyenne . "</p>";
                            echo "<p><u>Compte ECTS :</u> " . $ects . "</p>";
                            echo "<p><u>Options choisies :</u>";
                            echo "
                                <table>
                                    <tr>
                                        <th>n°</th>
                                ";

                            for ($j=5; $j<count($data); $j++) {
                                array_push($choix, $data[$j]);
                                echo "<th>".($j - 4)."</th>";
                            }

                            echo "</tr><tr><td>Option</td>";

                            for ($j=0; $j<count($choix); $j++) {
                                echo "<td>".$choix[$j]."</td>";
                            }
                            echo "</tr></table></p>";

                            break;
                        }
                    }
                    fclose($handle);
                }
            }

            if (!$user_found) {
                echo "<p>Aucune donnée n'a été trouvée pour vous.</p>";
            }
        ?>
    </body>
</html>