<?php
    if(
        isset($_GET['dest'])
        && $_GET['dest'] !== ''
        && isset($_GET['heure'])
        && $_GET['heure'] !== ''
        && isset($_GET['message'])
        && $_GET['message'] !== ''
    ) {
        if(
            ($handle = fopen("../../../../backend/db/messagerie.csv", "r")) !== FALSE 
            && ($fp = fopen("../../../../backend/db/messagerie_temp.csv", "a+")) !== FALSE
        ) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (
                    $data[1] !== $_GET['heure']
                    || $data[3] !== $_GET['dest']
                    || $data[4] !== $_GET['message']
                ) { // si la ligne ne correspond pas a celle qu'on cherche
                    fputcsv($fp, $data);                                // ecrit les données dans le fichier temporaire
                }
            }
            fclose($fp);
            fclose($handle);
            rename("../../../../backend/db/messagerie_temp.csv", "../../../../backend/db/messagerie.csv");
        }
    }
?>