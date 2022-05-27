<?php
    if(
        isset($_GET['dest'])
        && $_GET['dest'] !== ''
        && isset($_GET['heure'])
        && $_GET['heure'] !== ''
        && isset($_GET['message'])
        && $_GET['message'] !== ''
    ) {
        // vérifier si le message n'a pas déjà été signalé
        if (($handle = fopen("../../../../backend/db/signalements.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (
                    $_GET['heure'] === $data[1]
                    && $_GET['dest'] === $data[2]
                    && $_GET['message'] === $data[4]
                ) {
                    echo 'Ce message a déjà été signalé.';
                    fclose($handle);
                    return;
                }
            }
            fclose($handle);
        }

        if(
            ($handle = fopen("../../../../backend/db/messagerie.csv", "r")) !== FALSE 
            && ($fp = fopen("../../../../backend/db/signalements.csv", "a+")) !== FALSE
        ) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (
                    $data[1] === $_GET['heure']
                    && $data[2] === $_GET['dest']
                    && $data[4] === $_GET['message']
                ) { // si la ligne ne correspond pas a celle qu'on cherche
                    array_push($data, $data[3]);
                    array_push($data, date("Y-m-d"));
                    array_push($data, date("H:i:s"));
                    fputcsv($fp, $data);                // ecrit la ligne dans le csv 'signalements'
                    echo 'success';
                    break;
                }
            }
            fclose($fp);
            fclose($handle);
        }
    }
?>