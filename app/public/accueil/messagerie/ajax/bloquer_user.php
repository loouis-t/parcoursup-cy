<?php

    if (
        isset($_GET['debloquer'])
        && $_GET['debloquer'] === 'true'
        && isset($_GET['dest'])
        && $_GET['dest'] !== ''
        && isset($_GET['bloqueur'])
        && $_GET['bloqueur'] !== ''
    ) {
        // débloquer l'utilisateur
        if(
            ($handle = fopen("../../../../backend/db/utilisateurs_bloques.csv", "r")) !== FALSE 
            && ($fp = fopen("../../../../backend/db/utilisateurs_bloques_temp.csv", "a+")) !== FALSE
        ) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (
                    $data[2] !== $_GET['bloqueur']
                    || $data[3] !== $_GET['dest']
                ) { // si la ligne ne correspond pas a celle qu'on cherche
                    fputcsv($fp, $data);                                // ecrit les données dans le fichier temporaire
                }
            }
            fclose($fp);
            fclose($handle);
            rename("../../../../backend/db/utilisateurs_bloques_temp.csv", "../../../../backend/db/utilisateurs_bloques.csv");
        }
        return;
    }

    if (
        isset($_GET['dest'])
        && $_GET['dest'] !== ''
        && isset($_GET['bloqueur'])
        && $_GET['bloqueur'] !== ''
        && isset($_GET['motif'])
        && $_GET['motif'] !== ''
    ) {
        // ajouter destinataire dans liste utilisateurs bloqués par utilisateur courant
        if (($handle = fopen("../../../../backend/db/utilisateurs_bloques.csv", "a+")) !== FALSE) {
            $current_blocage = [ date("Y-m-d"), date("H:i:s"), $_GET['bloqueur'], $_GET['dest'], htmlspecialchars($_GET['motif']) ]; // date, heure, signaleur, signalé, motif
            fputcsv($handle, $current_blocage); // ecrit la ligne dans le csv 'utilisateurs_bloques.csv'
            fclose($handle);                    // ferme le fichier
        }
    }
?>