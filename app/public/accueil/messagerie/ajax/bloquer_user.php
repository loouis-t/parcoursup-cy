<?php
    if (
        isset($_GET['dest'])
        && $_GET['dest'] !== ''
        && isset($_GET['signaleur'])
        && $_GET['signaleur'] !== ''
        && isset($_GET['motif'])
        && $_GET['motif'] !== ''
    ) {
        // vérifier si le destinataire n'a pas déjà été bloqué
        if (($handle = fopen("../../../../backend/db/utilisateurs_bloques.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (
                    $data[2] === $_GET['signaleur']
                    && $data[3] === $_GET['dest']
                ) {
                    echo "Vous avez déja bloqué cet utilisateur.";
                    return;
                }
            }
        }

        // ajouter destinataire dans liste utilisateurs bloqués par utilisateur courant
        if (($handle = fopen("../../../../backend/db/utilisateurs_bloques.csv", "a+")) !== FALSE) {
            $current_blocage = [ date("Y-m-d"), date("H:i:s"), $_GET['signaleur'], $_GET['dest'], htmlspecialchars($_GET['motif']) ]; // date, heure, signaleur, signalé, motif
            fputcsv($handle, $current_blocage); // ecrit la ligne dans le csv 'utilisateurs_bloques.csv'
            fclose($handle);                    // ferme le fichier

            echo "success";                     // retour succes ajax
        }
    }
?>